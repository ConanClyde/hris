<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\DTOs\ContextDTO;
use App\Features\Calendar\Services\HolidayTextFormatter;
use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AIChatbotContextService
{
    private const PROMPT_STEM_TO_FILE = [
        'labor_code_leaves' => 'labor_code_leaves.txt',
        'csc_leave_policies' => 'csc_leave_policies.txt',
        'csc_leave_rules' => 'csc_leave_rules.txt',
        'pds_policies' => 'pds_policies.txt',
        'code_of_conduct' => 'code_of_conduct.txt',
        'ssl_vi_policies' => 'ssl_vi_policies.txt',
        'gsis_policies' => 'gsis_policies.txt',
        'spms_policies' => 'spms_policies.txt',
        'paternity_leave' => 'paternity_leave_policies.txt',
        'solo_parent_leave' => 'solo_parent_leave_policies.txt',
        'special_leave_women' => 'special_leave_women_policies.txt',
        'year_end_bonus' => 'year_end_bonus_policies.txt',
        'mid_year_bonus' => 'mid_year_bonus_policies.txt',
        'pbb_policies' => 'pbb_policies.txt',
        'policy_access' => 'policy_access.txt',
    ];

    public function __construct(
        private AIChatbotRetrievalService $retrievalService,
        private AIChatbotToolService $toolService,
        private AIChatbotAnalysisService $analysisService,
        private HolidayTextFormatter $holidayFormatter,
        private SourceLabelService $sourceLabelService,
        private AIChatbotUserProfileService $profileService
    ) {}

    public function getContext(User $user, ?string $query = null): ContextDTO
    {
        $userRole = (string) $user->role;
        $retrieval = ['snippets' => [], 'meta' => []];
        if (config('ai_chatbot.enable_retrieval', true)) {
            $limit = (int) config('ai_chatbot.max_sources', 3);
            $topics = $this->profileService->getFavoriteTopics($user);
            $retrieval = $this->retrievalService->retrieve((string) ($query ?? ''), max(1, $limit), $userRole, $topics);
        }
        $policySnippets = $this->capAndLabelSnippets($retrieval['snippets'] ?? []);
        $retrievalMeta = $retrieval['meta'] ?? [];

        $stats = [];
        if (in_array($userRole, [UserRole::Hr->value, UserRole::Admin->value], true)) {
            $stats = $this->toolService->getContextStatsForRole($user);
        }

        return new ContextDTO(
            role: $userRole,
            stats: $stats,
            employeeList: '',
            employeeData: [],
            policySnippets: $policySnippets,
            retrievalMeta: $retrievalMeta,
            generatedAt: now()->toDateTimeString(),
        );
    }

    /**
     * Resolve context from AI analysis. Returns ContextDTO or permission_denied on access failure.
     *
     * @return ContextDTO|array{permission_denied: true, message: string}
     */
    public function resolveFromAnalysis(array $analysis, User $user, string $query): ContextDTO|array
    {
        $userRole = (string) $user->role;

        if (! $this->analysisService->userHasRequiredRole($userRole, $analysis['min_required_role'] ?? $userRole)) {
            Log::info('AI analysis permission denied', [
                'user_id' => $user->id,
                'user_role' => $userRole,
                'min_required_role' => $analysis['min_required_role'] ?? 'unknown',
                'topic' => $analysis['topic_summary'] ?? '',
            ]);

            return [
                'permission_denied' => true,
                'message' => 'This question requires '.$this->roleLabel($analysis['min_required_role'] ?? 'admin').' access. Please contact your HR administrator or system admin if you need this information.',
            ];
        }

        $policySnippets = [];
        $retrievalMeta = [];

        if (config('ai_chatbot.enable_retrieval', true)) {
            $limit = (int) config('ai_chatbot.max_sources', 3);
            $topics = $this->profileService->getFavoriteTopics($user);
            $retrieval = $this->retrievalService->retrieve($query, max(1, $limit), $userRole, $topics);
            $policySnippets = $this->capAndLabelSnippets($retrieval['snippets'] ?? []);
            $retrievalMeta = $retrieval['meta'] ?? [];
        }

        if (! empty($analysis['requires_google_calendar'])) {
            $holidayText = $this->holidayFormatter->formatUpcomingHolidays(5);
            $policySnippets[] = [
                'excerpt' => "**Upcoming Calendar Holidays:**\n{$holidayText}",
                'source' => 'calendar',
                'confidence' => 1.0,
            ];
        }

        if (! empty($analysis['requires_markdown_prompts'])) {
            $maxChars = (int) config('ai_chatbot.max_policy_chars', 20000);
            $loaded = 0;
            foreach ($analysis['requires_markdown_prompts'] as $stem) {
                $filename = self::PROMPT_STEM_TO_FILE[$stem] ?? $stem.'.txt';
                $base = storage_path('app/prompts/'.pathinfo($filename, PATHINFO_FILENAME));
                $path = file_exists($base.'.txt') ? $base.'.txt' : (file_exists($base.'.md') ? $base.'.md' : null);
                if ($path !== null) {
                    $content = file_get_contents($path);
                    if (is_string($content) && $content !== '') {
                        $remaining = max(0, $maxChars - $loaded);
                        $excerpt = mb_substr($content, 0, min(mb_strlen($content), $remaining));
                        $loaded += mb_strlen($excerpt);
                        $policySnippets[] = [
                            'excerpt' => $excerpt,
                            'source' => basename($path),
                            'confidence' => 0.95,
                        ];
                        if ($loaded >= $maxChars) {
                            break;
                        }
                    }
                }
            }
        }

        $stats = [];
        if (! empty($analysis['requires_database']) && in_array($userRole, [UserRole::Hr->value, UserRole::Admin->value], true)) {
            $stats = $this->toolService->getContextStatsForRole($user);
        }

        $policySnippets = $this->capAndLabelSnippets($policySnippets);

        return new ContextDTO(
            role: $userRole,
            stats: $stats,
            employeeList: '',
            employeeData: [],
            policySnippets: $policySnippets,
            retrievalMeta: $retrievalMeta,
            generatedAt: now()->toDateTimeString(),
        );
    }

    /**
     * Group snippets by policy family, take at most 1 per group, cap total, add display_name.
     *
     * @param  array<int, array{source: string, excerpt?: string, confidence?: float, url?: string}>  $snippets
     * @return array<int, array{source: string, display_name: string, excerpt?: string, confidence?: float, url?: string}>
     */
    private function capAndLabelSnippets(array $snippets): array
    {
        $maxSources = (int) config('ai_chatbot.max_sources', 3);
        $maxSources = max(1, min($maxSources, 5));

        $grouped = [];
        foreach ($snippets as $snippet) {
            $source = (string) ($snippet['source'] ?? '');
            if ($source === '') {
                continue;
            }
            $group = $this->sourceLabelService->getPolicyGroup($source);
            if (! isset($grouped[$group])) {
                $grouped[$group] = $snippet;
            }
        }

        $capped = array_slice(array_values($grouped), 0, $maxSources);

        return $this->sourceLabelService->addDisplayNames($capped);
    }

    private function roleLabel(string $role): string
    {
        return match ($role) {
            UserRole::Admin->value => 'Admin',
            UserRole::Hr->value => 'HR',
            default => 'elevated',
        };
    }
}
