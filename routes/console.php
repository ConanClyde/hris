<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ai:reindex-docs', function () {
    $count = app(\App\Features\AIChatbot\Services\AIChatbotDocumentIndexer::class)->indexPrompts();
    $this->info("Indexed {$count} document(s).");
})->purpose('Reindex AI chatbot policy documents');

Artisan::command('ai:ingest-docs {--embed=1}', function () {
    $embed = filter_var($this->option('embed'), FILTER_VALIDATE_BOOLEAN);
    $result = app(\App\Features\AIChatbot\Services\AIChatbotIngestionService::class)->ingestPrompts($embed);

    $this->info("Documents indexed: {$result['documents_indexed']}");
    $this->info("Chunks created: {$result['chunks_created']}");
})->purpose('Ingest AI chatbot documents and embeddings');

Artisan::command('ai:evaluate', function () {
    $path = storage_path('app/ai_eval/golden_set.json');
    if (! file_exists($path)) {
        $this->error('Golden set file not found at storage/app/ai_eval/golden_set.json');

        return;
    }

    $payload = json_decode((string) file_get_contents($path), true);
    if (! is_array($payload)) {
        $this->error('Invalid golden set format.');

        return;
    }

    $retrieval = app(\App\Features\AIChatbot\Services\AIChatbotRetrievalService::class);
    $total = 0;
    $precisionSum = 0.0;

    foreach ($payload as $case) {
        $query = (string) ($case['query'] ?? '');
        $expected = array_map('strval', $case['expected_sources'] ?? []);
        if ($query === '' || $expected === []) {
            continue;
        }

        $result = $retrieval->retrieve($query, 3);
        $sources = array_map(fn ($item) => (string) ($item['source'] ?? ''), $result['snippets'] ?? []);
        $matched = array_values(array_intersect($sources, $expected));
        $precision = count($sources) > 0 ? count($matched) / count($sources) : 0.0;
        $precisionSum += $precision;
        $total++;
    }

    if ($total === 0) {
        $this->error('No valid evaluation cases found.');

        return;
    }

    $avgPrecision = round($precisionSum / $total, 3);
    $this->info("Evaluated {$total} cases.");
    $this->info("Average retrieval precision: {$avgPrecision}");
})->purpose('Evaluate AI retrieval quality using a golden set');

Artisan::command('ai:enhancement-audit {--hours=} {--fail-on-regression}', function () {
    $defaultHours = (int) config('ai_chatbot.enhancement.monitoring.default_window_hours', 168);
    $hoursInput = $this->option('hours');
    $hours = (is_string($hoursInput) && $hoursInput !== '') ? (int) $hoursInput : $defaultHours;
    $hours = max(1, min(720, $hours));

    $report = app(\App\Features\AIChatbot\Services\AIChatbotEnhancementFrameworkService::class)->buildReport($hours);
    $status = (string) ($report['overall']['status'] ?? 'needs_attention');
    $score = (float) ($report['overall']['score'] ?? 0.0);
    $degraded = (array) ($report['overall']['degraded_areas'] ?? []);

    $this->info('AI enhancement framework audit generated.');
    $this->info('Window hours: '.$hours);
    $this->info('Overall status: '.$status);
    $this->info('Overall score: '.number_format($score, 3));
    $this->line('Degraded areas: '.($degraded === [] ? 'none' : implode(', ', $degraded)));

    $reportRelative = (string) config('ai_chatbot.enhancement.monitoring.report_path', 'app/ai_eval/enhancement_audit_latest.json');
    $reportPath = storage_path(str_starts_with($reportRelative, 'app/') ? substr($reportRelative, 4) : $reportRelative);
    $dir = dirname($reportPath);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($reportPath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    $snapshotPath = preg_replace('/\.json$/', '', $reportPath).'_'.now()->format('Ymd_His').'.json';
    file_put_contents((string) $snapshotPath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $this->info('Report saved to: '.$reportPath);

    $retentionDays = (int) config('ai_chatbot.enhancement.monitoring.snapshot_retention_days', 30);
    $cutoff = now()->subDays(max(1, $retentionDays))->getTimestamp();
    foreach (glob(preg_replace('/\.json$/', '', $reportPath).'_*.json') ?: [] as $snapshotFile) {
        if (@filemtime($snapshotFile) !== false && (int) filemtime($snapshotFile) < $cutoff) {
            @unlink($snapshotFile);
        }
    }

    if ($this->option('fail-on-regression') && $status !== 'on_track') {
        return \Illuminate\Console\Command::FAILURE;
    }

    return \Illuminate\Console\Command::SUCCESS;
})->purpose('Generate AI enhancement framework report with targets and benchmarks');

Artisan::command('leave:accrue-monthly {--month=}', function () {
    $month = $this->option('month');
    $result = app(\App\Features\Leave\Services\LeaveAccrualService::class)
        ->accrueMonthly(
            is_string($month) && $month !== ''
                ? \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth()
                : null,
        );

    $this->info('Accrual month: '.$result['month']);
    $this->info('Adjustments created: '.$result['adjustments_created']);
})->purpose('Accrue monthly leave credits (VL/SL)');

Artisan::command('leave:reset-annual {--year=}', function () {
    $yearOpt = $this->option('year');
    $year = (is_string($yearOpt) && $yearOpt !== '') ? (int) $yearOpt : null;
    $result = app(\App\Features\Leave\Services\LeaveAccrualService::class)
        ->resetAnnualNonCumulative($year);

    $this->info('Reset year: '.$result['year']);
    $this->info('Leave types reset processed: '.$result['resets_processed']);
})->purpose('Reset annual non-cumulative leave buckets (Wellness, etc.)');

Schedule::command('ai:ingest-docs --embed=1')->dailyAt('02:00');

Schedule::command('ai-chat:purge-old --days=7')->dailyAt('02:30');

Schedule::command('ai:enhancement-audit --hours=168')->hourly();

Schedule::command('leave:accrue-monthly')->monthlyOn(1, '00:01');

Schedule::command('badges:check')->dailyAt('01:00');

Schedule::command('digest:send-daily')->dailyAt('07:00');

Schedule::command('alerts:check-expiring --days=30')->dailyAt('07:30');
