<?php

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\Models\AIChatbotDocument;
use App\Features\AIChatbot\Models\AIChatbotFeedback;
use App\Features\AIChatbot\Models\AIChatbotMetric;
use Illuminate\Support\Facades\Schema;

class AIChatbotEnhancementFrameworkService
{
    public function buildReport(int $hours = 168): array
    {
        $windowHours = max(1, $hours);
        $since = now()->subHours($windowHours);
        $summary = $this->collectSummary($since, $windowHours);
        $targets = (array) config('ai_chatbot.enhancement.targets', []);
        $benchmarks = (array) config('ai_chatbot.enhancement.industry_benchmarks', []);
        $roadmap = (array) config('ai_chatbot.enhancement.roadmap', []);
        $testing = (array) config('ai_chatbot.enhancement.testing_protocols', []);
        $monitoring = (array) config('ai_chatbot.enhancement.monitoring', []);

        $areas = [
            $this->buildArea('intelligence', $summary, $targets, $benchmarks, $roadmap, $testing),
            $this->buildArea('accuracy', $summary, $targets, $benchmarks, $roadmap, $testing),
            $this->buildArea('efficiency', $summary, $targets, $benchmarks, $roadmap, $testing),
            $this->buildArea('hallucination_mitigation', $summary, $targets, $benchmarks, $roadmap, $testing),
            $this->buildArea('source_validation', $summary, $targets, $benchmarks, $roadmap, $testing),
            $this->buildArea('additional_improvements', $summary, $targets, $benchmarks, $roadmap, $testing),
        ];

        $overallScore = count($areas) > 0
            ? round(array_sum(array_column($areas, 'score')) / count($areas), 3)
            : 0.0;

        $degraded = array_values(array_map(
            fn (array $item) => $item['key'],
            array_filter($areas, fn (array $item): bool => $item['status'] !== 'on_track')
        ));

        return [
            'generated_at' => now()->toIso8601String(),
            'window_hours' => $windowHours,
            'overall' => [
                'score' => $overallScore,
                'status' => $degraded === [] ? 'on_track' : 'needs_attention',
                'degraded_areas' => $degraded,
            ],
            'summary' => $summary,
            'areas' => $areas,
            'roadmap' => $roadmap,
            'testing_protocols' => $testing,
            'benchmarking' => [
                'industry_standards' => $benchmarks,
                'target_thresholds' => $targets,
            ],
            'monitoring' => $monitoring,
        ];
    }

    private function collectSummary(\Carbon\CarbonInterface $since, int $windowHours): array
    {
        $minConfidence = (float) config('ai_chatbot.min_confidence', 0.35);
        $metricRows = collect();
        if (Schema::hasTable('ai_chatbot_metrics')) {
            $metricRows = AIChatbotMetric::query()
                ->where('created_at', '>=', $since)
                ->get(['total_ms', 'context_ms', 'llm_ms', 'max_confidence', 'cache_hit', 'error_type', 'policy_sources_count']);
        }

        $feedbackRows = collect();
        if (Schema::hasTable('ai_chatbot_feedback')) {
            $feedbackRows = AIChatbotFeedback::query()
                ->where('created_at', '>=', $since)
                ->get(['rating', 'sources']);
        }

        $docRows = collect();
        if (Schema::hasTable('ai_chatbot_documents')) {
            $docRows = AIChatbotDocument::query()->get(['updated_at']);
        }

        $totalQueries = $metricRows->count();
        $cacheHits = (int) $metricRows->where('cache_hit', true)->count();
        $errorCount = (int) $metricRows->filter(fn ($row) => ! empty($row->error_type))->count();
        $lowConfidenceCount = (int) $metricRows->filter(fn ($row) => (float) $row->max_confidence < $minConfidence)->count();
        $sourcedCount = (int) $metricRows->filter(fn ($row) => (int) ($row->policy_sources_count ?? 0) > 0)->count();

        $upVotes = (int) $feedbackRows->where('rating', 1)->count();
        $downVotes = (int) $feedbackRows->where('rating', -1)->count();
        $feedbackWithSources = (int) $feedbackRows->filter(function ($row): bool {
            $sources = $row->sources;

            return is_array($sources) && count($sources) > 0;
        })->count();

        $now = now();
        $staleDays = (int) config('ai_chatbot.policy_stale_days', 180);
        $healthyDocs = (int) $docRows->filter(function ($row) use ($now, $staleDays): bool {
            if (! $row->updated_at) {
                return false;
            }

            return $row->updated_at->diffInDays($now) <= $staleDays;
        })->count();

        return [
            'queries_total' => $totalQueries,
            'throughput_queries_per_hour' => round($totalQueries / max(1, $windowHours), 3),
            'p95_total_ms' => $this->percentile($metricRows->pluck('total_ms')->map(fn ($v) => (float) $v)->all(), 95),
            'avg_context_ms' => round((float) $metricRows->avg('context_ms'), 3),
            'avg_llm_ms' => round((float) $metricRows->avg('llm_ms'), 3),
            'avg_confidence' => round((float) $metricRows->avg('max_confidence'), 3),
            'cache_hit_rate' => $this->rate($cacheHits, $totalQueries),
            'error_rate' => $this->rate($errorCount, $totalQueries),
            'low_confidence_rate' => $this->rate($lowConfidenceCount, $totalQueries),
            'source_coverage_rate' => $this->rate($sourcedCount, $totalQueries),
            'feedback_total' => $feedbackRows->count(),
            'feedback_positive_rate' => $this->rate($upVotes, $upVotes + $downVotes),
            'feedback_negative_rate' => $this->rate($downVotes, $upVotes + $downVotes),
            'feedback_source_coverage_rate' => $this->rate($feedbackWithSources, $feedbackRows->count()),
            'documents_total' => $docRows->count(),
            'document_freshness_rate' => $this->rate($healthyDocs, $docRows->count()),
            'analysis_pipeline_enabled' => (bool) config('ai_chatbot.enable_analysis_pipeline', true),
            'response_cache_enabled' => (bool) config('ai_chatbot.enable_response_cache', true),
            'retrieval_enabled' => (bool) config('ai_chatbot.enable_retrieval', true),
            'embeddings_enabled' => (bool) config('ai_chatbot.enable_embeddings', true),
        ];
    }

    private function buildArea(
        string $areaKey,
        array $summary,
        array $targets,
        array $benchmarks,
        array $roadmap,
        array $testing
    ): array {
        $metrics = match ($areaKey) {
            'intelligence' => [
                $this->metric($areaKey, 'analysis_pipeline_uptime', $summary['analysis_pipeline_enabled'] ? 1.0 : 0.0, 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'domain_context_coverage', (float) $summary['source_coverage_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'continuous_tuning_readiness', (float) $summary['document_freshness_rate'], 'ratio', 'min', $targets, $benchmarks),
            ],
            'accuracy' => [
                $this->metric($areaKey, 'avg_confidence', (float) $summary['avg_confidence'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'reliability_rate', 1.0 - (float) $summary['error_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'consensus_stability', 1.0 - (float) $summary['low_confidence_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'real_time_correction_rate', (float) $summary['feedback_positive_rate'], 'ratio', 'min', $targets, $benchmarks),
            ],
            'efficiency' => [
                $this->metric($areaKey, 'p95_total_ms', (float) $summary['p95_total_ms'], 'ms', 'max', $targets, $benchmarks),
                $this->metric($areaKey, 'cache_hit_rate', (float) $summary['cache_hit_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'throughput_queries_per_hour', (float) $summary['throughput_queries_per_hour'], 'qph', 'min', $targets, $benchmarks),
            ],
            'hallucination_mitigation' => [
                $this->metric($areaKey, 'low_confidence_rate', (float) $summary['low_confidence_rate'], 'ratio', 'max', $targets, $benchmarks),
                $this->metric($areaKey, 'negative_feedback_rate', (float) $summary['feedback_negative_rate'], 'ratio', 'max', $targets, $benchmarks),
                $this->metric($areaKey, 'grounded_response_rate', (float) $summary['source_coverage_rate'], 'ratio', 'min', $targets, $benchmarks),
            ],
            'source_validation' => [
                $this->metric($areaKey, 'citation_coverage_rate', (float) $summary['source_coverage_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'feedback_source_coverage_rate', (float) $summary['feedback_source_coverage_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'document_freshness_rate', (float) $summary['document_freshness_rate'], 'ratio', 'min', $targets, $benchmarks),
            ],
            default => [
                $this->metric($areaKey, 'adversarial_resilience_rate', 1.0 - (float) $summary['error_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'explainability_rate', (float) $summary['source_coverage_rate'], 'ratio', 'min', $targets, $benchmarks),
                $this->metric($areaKey, 'ethics_compliance_rate', (float) $summary['document_freshness_rate'], 'ratio', 'min', $targets, $benchmarks),
            ],
        };

        $passRate = count($metrics) > 0
            ? round(count(array_filter($metrics, fn (array $item): bool => (bool) $item['meets_target'])) / count($metrics), 3)
            : 0.0;

        return [
            'key' => $areaKey,
            'score' => $passRate,
            'status' => $passRate >= 0.67 ? 'on_track' : 'needs_attention',
            'metrics' => $metrics,
            'roadmap' => (array) ($roadmap[$areaKey] ?? []),
            'testing_protocols' => (array) ($testing[$areaKey] ?? []),
        ];
    }

    private function metric(
        string $areaKey,
        string $metricKey,
        float $current,
        string $unit,
        string $direction,
        array $targets,
        array $benchmarks
    ): array {
        $target = (float) ($targets[$areaKey][$metricKey] ?? 0.0);
        $benchmark = (float) ($benchmarks[$areaKey][$metricKey] ?? 0.0);
        $meetsTarget = $direction === 'max' ? $current <= $target : $current >= $target;

        return [
            'key' => $metricKey,
            'current' => round($current, 3),
            'target' => round($target, 3),
            'industry_benchmark' => round($benchmark, 3),
            'unit' => $unit,
            'direction' => $direction,
            'meets_target' => $meetsTarget,
        ];
    }

    private function rate(int $numerator, int $denominator): float
    {
        if ($denominator <= 0) {
            return 0.0;
        }

        return round($numerator / $denominator, 3);
    }

    private function percentile(array $values, int $percentile): float
    {
        if ($values === []) {
            return 0.0;
        }

        sort($values);
        $rank = (int) ceil(($percentile / 100) * count($values));
        $index = max(0, min(count($values) - 1, $rank - 1));

        return round((float) $values[$index], 3);
    }
}
