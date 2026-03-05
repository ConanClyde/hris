<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Services;

class MessageBuilderService
{
    public function __construct(
        private AIChatbotSuggestionService $suggestionService
    ) {}

    /**
     * Build messages array for AI model.
     */
    public function buildMessages(
        array $history,
        string $userMessage,
        string $userRole,
        array $systemStats,
        string $employeeList,
        array $employeeData,
        array $policySnippets
    ): array {
        $systemPrompt = $this->buildSystemPrompt($userRole, $systemStats, $employeeList, $employeeData, $policySnippets);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add history
        foreach ($history as $msg) {
            $role = $msg['role'] ?? 'user';
            $content = $msg['content'] ?? '';
            if ($content !== '') {
                $messages[] = ['role' => $role, 'content' => $content];
            }
        }

        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        return $messages;
    }

    /**
     * Build system prompt with comprehensive context and strict accuracy constraints.
     */
    private function buildSystemPrompt(
        string $userRole,
        array $systemStats,
        string $employeeList,
        array $employeeData,
        array $policySnippets
    ): string {
        $prompt = "You are an expert HR Assistant for an IT company's HRIS system. ";
        $prompt .= "Your primary goal is to provide ACCURATE, PRECISE, and RELIABLE information. ";
        $prompt .= "The user has the role: {$userRole}.\n\n";

        // Critical accuracy instructions
        $prompt .= "ACCURACY RULES (MUST FOLLOW):\n";
        $prompt .= "1. ONLY state facts that are explicitly provided in the system context below.\n";
        $prompt .= "2. If data shows 0 records, explicitly say 'There are 0 records' - NEVER invent numbers.\n";
        $prompt .= "3. When citing statistics, ALWAYS include the timestamp to show data freshness.\n";
        $prompt .= "4. If you're uncertain about any information, say 'I don't have that information' rather than guessing.\n";
        $prompt .= "5. For leave/training counts, ONLY use the exact numbers from system_stats tool results.\n";
        $prompt .= "6. NEVER assume or hallucinate employee names, dates, or status values.\n";
        $prompt .= "7. If asked about 'today', use the timestamp in the context to determine the current date.\n\n";

        // Chain of thought instruction for complex queries
        $prompt .= "REASONING PROCESS:\n";
        $prompt .= "For complex questions, briefly think step-by-step:\n";
        $prompt .= "1. Identify what specific data is needed from the context.\n";
        $prompt .= "2. Locate that exact data in the system context provided.\n";
        $prompt .= "3. Formulate your answer based ONLY on that data.\n";
        $prompt .= "4. If data is missing, explicitly state what information is unavailable.\n\n";

        // Add policy snippets if available
        if ($policySnippets !== []) {
            $prompt .= "RELEVANT POLICIES (Use these for policy questions):\n";
            foreach ($policySnippets as $snippet) {
                $excerpt = $snippet['excerpt'] ?? '';
                $source = $snippet['source'] ?? 'HR Policy';
                if ($excerpt !== '') {
                    $prompt .= "- [{$source}] {$excerpt}\n";
                }
            }
            $prompt .= "\n";
        }

        // Add system stats if available
        if ($systemStats !== []) {
            $prompt .= "CURRENT SYSTEM DATA (Use ONLY these numbers - do not estimate):\n";
            $prompt .= json_encode($systemStats, JSON_PRETTY_PRINT);
            $prompt .= "\n\n";
        }

        // Add employee data context
        if ($employeeList !== '') {
            $prompt .= "EMPLOYEE REFERENCE DATA:\n{$employeeList}\n\n";
        }

        // Response format guidelines
        $prompt .= "RESPONSE GUIDELINES:\n";
        $prompt .= "- Be concise and direct. Avoid unnecessary pleasantries.\n";
        $prompt .= "- When giving numbers, always double-check against the system data above.\n";
        $prompt .= "- Format dates clearly (e.g., 'March 5, 2026').\n";
        $prompt .= "- If making recommendations, clearly label them as 'Recommendation:' separate from facts.\n";
        $prompt .= "- Always cite your source (e.g., 'According to the system data as of [timestamp]...').\n";

        return $prompt;
    }

    /**
     * Compress history to fit within token limits.
     */
    public function compressHistory(array $history, int $maxMessages = 20): array
    {
        if (count($history) <= $maxMessages) {
            return $history;
        }

        // Keep the most recent messages, ensure alternating pattern
        $compressed = array_slice($history, -$maxMessages);

        // Ensure conversation starts with user message
        if ($compressed[0]['role'] ?? '' === 'assistant') {
            array_shift($compressed);
        }

        return $compressed;
    }
}
