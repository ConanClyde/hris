<?php

namespace App\Features\AIChatbot\Services;

use App\Features\Users\Enums\UserRole;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatbotAnalysisService
{
    private const TIMEOUT_SECONDS = 8;

    private const ROLE_ORDER = [
        UserRole::Employee->value => 0,
        UserRole::Hr->value => 1,
        UserRole::Admin->value => 2,
    ];

    /**
     * Analyze user prompt and return structured requirements, or null on failure (timeout/parse error).
     *
     * @return array{requires_google_calendar: bool, requires_database: array, requires_markdown_prompts: array, min_required_role: string, topic_summary: string}|null
     */
    public function analyzePrompt(string $userMessage, string $userRole): ?array
    {
        $model = (string) config('services.ollama.model', 'llama3.2:3b');
        $baseUrl = rtrim((string) config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');
        $chatUrl = $baseUrl.'/api/chat';

        $systemPrompt = $this->buildAnalysisPrompt();
        $userPrompt = "User role: {$userRole}\nQuery: ".trim($userMessage);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->retry(0, 0, throw: false)
                ->post($chatUrl, [
                    'model' => $model,
                    'messages' => $messages,
                    'stream' => false,
                    'options' => [
                        'temperature' => 0.1,
                        'num_ctx' => 2048,
                        'num_predict' => 256,
                    ],
                ]);

            if (! $response->successful()) {
                Log::warning('AI analysis request failed', [
                    'status' => $response->status(),
                    'body' => mb_substr($response->body(), 0, 500),
                ]);

                return null;
            }

            $text = $response->json('message.content');
            if (! is_string($text) || trim($text) === '') {
                return null;
            }

            return $this->parseAnalysisResponse($text, $userRole);
        } catch (\Throwable $e) {
            Log::warning('AI analysis exception', ['message' => $e->getMessage()]);

            return null;
        }
    }

    private function buildAnalysisPrompt(): string
    {
        return <<<'PROMPT'
Analyze this HRIS user query and return ONLY valid JSON with these exact keys (no other text, no markdown):

{
  "requires_google_calendar": false,
  "requires_database": [],
  "requires_markdown_prompts": [],
  "min_required_role": "employee",
  "topic_summary": "short phrase"
}

Rules:
- requires_google_calendar: true only if the query is about holidays, calendar events, upcoming dates, or special non-working days
- requires_database: array of categories if live data is needed. Valid values: users, employees, leave_applications, trainings, pds, activity_logs. Use [] if not needed
- requires_markdown_prompts: array of policy file stems if policy content is needed. Valid: labor_code_leaves, csc_leave_policies, pds_policies, code_of_conduct, ssl_vi_policies, gsis_policies, spms_policies, paternity_leave, solo_parent_leave, special_leave_women, year_end_bonus, mid_year_bonus, pbb_policies. Use [] if not needed
- min_required_role: "employee" | "hr" | "admin" - the minimum role needed to answer. Use "employee" for general policy questions, "hr" for HR data, "admin" for system/admin data
- topic_summary: one short phrase describing the topic (e.g. "leave policy", "holiday calendar", "employee count")
PROMPT;
    }

    /**
     * @return array{requires_google_calendar: bool, requires_database: array, requires_markdown_prompts: array, min_required_role: string, topic_summary: string}|null
     */
    private function parseAnalysisResponse(string $text, string $userRole): ?array
    {
        $text = trim($text);
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = preg_replace('/\s*```\s*$/', '', $text);
        $text = trim($text);

        $decoded = json_decode($text, true);
        if (! is_array($decoded)) {
            return null;
        }

        $requiresCalendar = (bool) ($decoded['requires_google_calendar'] ?? false);
        $requiresDb = $decoded['requires_database'] ?? [];
        $requiresDb = is_array($requiresDb) ? array_values(array_filter($requiresDb, 'is_string')) : [];
        $requiresPrompts = $decoded['requires_markdown_prompts'] ?? [];
        $requiresPrompts = is_array($requiresPrompts) ? array_values(array_filter($requiresPrompts, 'is_string')) : [];
        $minRole = (string) ($decoded['min_required_role'] ?? $userRole);
        $topicSummary = (string) ($decoded['topic_summary'] ?? 'general');

        $validRoles = [UserRole::Employee->value, UserRole::Hr->value, UserRole::Admin->value];
        if (! in_array($minRole, $validRoles, true)) {
            $minRole = $userRole;
        }

        return [
            'requires_google_calendar' => $requiresCalendar,
            'requires_database' => $requiresDb,
            'requires_markdown_prompts' => $requiresPrompts,
            'min_required_role' => $minRole,
            'topic_summary' => $topicSummary === '' ? 'general' : $topicSummary,
        ];
    }

    private function defaultAnalysis(string $userRole): array
    {
        return [
            'requires_google_calendar' => false,
            'requires_database' => [],
            'requires_markdown_prompts' => [],
            'min_required_role' => $userRole,
            'topic_summary' => 'general',
        ];
    }

    public function userHasRequiredRole(string $userRole, string $minRequiredRole): bool
    {
        $userLevel = self::ROLE_ORDER[$userRole] ?? -1;
        $requiredLevel = self::ROLE_ORDER[$minRequiredRole] ?? 999;

        return $userLevel >= $requiredLevel;
    }
}
