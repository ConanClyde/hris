<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerfMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerfMetricsController extends Controller
{
    /**
     * Handle incoming performance metrics beacon.
     */
    public function store(Request $request)
    {
        // Metric payloads are often sent as Blob/text via sendBeacon,
        // handle JSON parsing manually if needed, or rely on Laravel's automatic parsing
        // if the content-type is correct (sendBeacon with Blob sets it to the Blob's type).

        $payload = $request->json()->all();

        if (empty($payload)) {
            // Fallback for raw body if json middleware didn't catch it
            $content = $request->getContent();
            $payload = json_decode($content, true);
        }

        if (! $payload) {
            return response()->noContent();
        }

        $ttfb = isset($payload['ttfb']) && is_numeric($payload['ttfb']) ? (int) $payload['ttfb'] : null;
        $lcp = isset($payload['lcp']) && is_numeric($payload['lcp']) ? (int) $payload['lcp'] : null;
        $navTransition = isset($payload['nav_transition_ms']) && is_numeric($payload['nav_transition_ms']) ? (int) $payload['nav_transition_ms'] : null;

        $context = [
            'route' => $payload['route'] ?? 'unknown',
            'fcp' => $payload['fcp'] ?? null,
            'lcp' => $payload['lcp'] ?? null,
            'cls' => $payload['cls'] ?? null,
            'ttfb' => $payload['ttfb'] ?? null,
            'dom_ready' => $payload['dom_ready'] ?? null,
            'page_load' => $payload['page_load'] ?? null,
            'nav_transition_ms' => $payload['nav_transition_ms'] ?? null,
            'user_id' => $payload['user_id'] ?? null,
            'role' => $payload['role'] ?? null,
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
        ];

        Log::info('[PERF] Client Metric', $context);

        if (! (bool) env('PERF_METRICS_PERSIST', true)) {
            return response()->noContent();
        }

        $shouldWarn = false;
        if ($ttfb !== null && $ttfb > 2000) {
            $shouldWarn = true;
        }
        if ($lcp !== null && $lcp > 3000) {
            $shouldWarn = true;
        }
        if ($navTransition !== null && $navTransition > 2000) {
            $shouldWarn = true;
        }

        try {
            PerfMetric::create([
                'route' => (string) ($payload['route'] ?? 'unknown'),
                'user_id' => isset($payload['user_id']) && is_numeric($payload['user_id']) ? (int) $payload['user_id'] : null,
                'role' => isset($payload['role']) ? (string) $payload['role'] : null,
                'fcp' => isset($payload['fcp']) && is_numeric($payload['fcp']) ? (int) $payload['fcp'] : null,
                'lcp' => $lcp,
                'cls' => isset($payload['cls']) && is_numeric($payload['cls']) ? (float) $payload['cls'] : null,
                'ttfb' => $ttfb,
                'dom_ready' => isset($payload['dom_ready']) && is_numeric($payload['dom_ready']) ? (int) $payload['dom_ready'] : null,
                'page_load' => isset($payload['page_load']) && is_numeric($payload['page_load']) ? (int) $payload['page_load'] : null,
                'nav_transition_ms' => $navTransition,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'budget_exceeded' => $shouldWarn,
                'payload' => $payload,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[PERF] Failed to persist client metric', [
                'error' => $e->getMessage(),
            ]);
        }

        if ($shouldWarn) {
            Log::warning('[PERF] Client Metric (budget exceeded)', $context);
        }

        return response()->noContent();
    }
}
