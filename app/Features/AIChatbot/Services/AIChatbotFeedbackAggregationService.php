<?php

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\Models\AIChatbotFeedback;
use Illuminate\Support\Collection;

class AIChatbotFeedbackAggregationService
{
    public function __construct(
        private SourceLabelService $sourceLabelService
    ) {}

    /**
     * Get most downvoted policy files from feedback sources.
     *
     * @return array<int, array{source: string, display_name: string, count: int}>
     */
    public function getMostDownvotedPolicyFiles(int $limit = 10): array
    {
        $rows = AIChatbotFeedback::query()
            ->where('rating', -1)
            ->whereNotNull('sources')
            ->get(['sources']);

        $counts = [];
        foreach ($rows as $row) {
            $sources = $row->sources;
            if (! is_array($sources)) {
                continue;
            }
            foreach ($sources as $item) {
                $source = is_array($item) ? ($item['source'] ?? null) : $item;
                if (is_string($source) && $source !== '') {
                    $counts[$source] = ($counts[$source] ?? 0) + 1;
                }
            }
        }

        arsort($counts);

        return Collection::make($counts)
            ->take($limit)
            ->map(fn (int $count, string $source) => [
                'source' => $source,
                'display_name' => $this->sourceLabelService->getDisplayName($source),
                'count' => $count,
            ])
            ->values()
            ->all();
    }

    /**
     * Get feedback summary for admin dashboard.
     *
     * @return array{total: int, helpful: int, not_helpful: int, top_failing: array, most_downvoted_sources: array}
     */
    public function getSummary(int $topFailingLimit = 5, int $downvotedSourcesLimit = 10): array
    {
        $total = AIChatbotFeedback::query()->count();
        $helpful = AIChatbotFeedback::query()->where('rating', 1)->count();
        $notHelpful = AIChatbotFeedback::query()->where('rating', -1)->count();

        $topFailing = AIChatbotFeedback::query()
            ->where('rating', -1)
            ->whereNotNull('prompt')
            ->selectRaw('prompt, COUNT(*) as total')
            ->groupBy('prompt')
            ->orderByDesc('total')
            ->limit($topFailingLimit)
            ->get()
            ->map(fn ($row) => [
                'prompt' => $row->prompt,
                'total' => (int) $row->total,
            ])
            ->all();

        $mostDownvotedSources = $this->getMostDownvotedPolicyFiles($downvotedSourcesLimit);

        return [
            'total' => $total,
            'helpful' => $helpful,
            'not_helpful' => $notHelpful,
            'top_failing' => $topFailing,
            'most_downvoted_sources' => $mostDownvotedSources,
        ];
    }
}
