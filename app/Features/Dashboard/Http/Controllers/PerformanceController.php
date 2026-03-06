<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PerformanceController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $diagnostics = $request->session()->get('performance_diagnostics');

        $memoryPeakBytes = memory_get_peak_usage(true);
        $memoryPeakMb = round($memoryPeakBytes / 1024 / 1024, 2);

        $memoryBytes = memory_get_usage(true);
        $memoryMb = round($memoryBytes / 1024 / 1024, 2);

        $opcache = function_exists('opcache_get_status') ? @opcache_get_status(false) : null;
        $opcacheEnabled = is_array($opcache) ? (bool) ($opcache['opcache_enabled'] ?? false) : false;

        $opcacheHitRate = null;
        $opcacheMemoryUsedMb = null;
        if (is_array($opcacheEnabled) || $opcacheEnabled) {
            if (is_array($opcache)) {
                $hits = (float) (($opcache['opcache_statistics']['hits'] ?? 0) ?: 0);
                $misses = (float) (($opcache['opcache_statistics']['misses'] ?? 0) ?: 0);
                $total = $hits + $misses;
                $opcacheHitRate = $total > 0 ? round(($hits / $total) * 100, 2) : null;

                $usedBytes = (float) (($opcache['memory_usage']['used_memory'] ?? 0) ?: 0);
                $opcacheMemoryUsedMb = $usedBytes > 0 ? round($usedBytes / 1024 / 1024, 2) : null;
            }
        }

        $dbDriver = (string) (Config::get('database.default') ?? '');
        $dbConnection = DB::connection();
        $dbPdo = $dbConnection->getPdo();
        $dbServerVersion = '';
        try {
            $dbServerVersion = (string) $dbPdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        } catch (\Throwable) {
            $dbServerVersion = '';
        }

        $storagePath = storage_path();
        $storageFreeBytes = @disk_free_space($storagePath);
        $storageFreeGb = is_numeric($storageFreeBytes)
            ? round(((float) $storageFreeBytes) / 1024 / 1024 / 1024, 2)
            : null;

        $storageWritable = @is_writable($storagePath);

        $queueDriver = (string) config('queue.default');
        $cacheDriver = (string) config('cache.default');

        $queuePendingCount = null;
        $queueFailedCount = null;
        if ($queueDriver === 'database') {
            try {
                $queuePendingCount = (int) DB::table('jobs')->count();
            } catch (\Throwable) {
                $queuePendingCount = null;
            }
            try {
                $queueFailedCount = (int) DB::table('failed_jobs')->count();
            } catch (\Throwable) {
                $queueFailedCount = null;
            }
        }

        return Inertia::render('Admin/Performance/Index', [
            'metrics' => [
                'app_env' => (string) config('app.env'),
                'app_debug' => (bool) config('app.debug'),
                'app_timezone' => (string) config('app.timezone'),

                'memory_mb' => $memoryMb,
                'memory_peak_mb' => $memoryPeakMb,
                'php_version' => PHP_VERSION,
                'php_sapi' => PHP_SAPI,
                'php_memory_limit' => (string) ini_get('memory_limit'),
                'php_max_execution_time' => (string) ini_get('max_execution_time'),
                'php_upload_max_filesize' => (string) ini_get('upload_max_filesize'),
                'php_post_max_size' => (string) ini_get('post_max_size'),
                'opcache_enabled' => $opcacheEnabled,
                'opcache_hit_rate' => $opcacheHitRate,
                'opcache_memory_used_mb' => $opcacheMemoryUsedMb,
                'laravel_version' => \Illuminate\Foundation\Application::VERSION,

                'db_connection' => $dbDriver,
                'db_driver' => (string) ($dbConnection->getDriverName() ?? ''),
                'db_server_version' => $dbServerVersion,

                'cache_driver' => $cacheDriver,
                'queue_driver' => $queueDriver,
                'queue_pending_count' => $queuePendingCount,
                'queue_failed_count' => $queueFailedCount,
                'mail_driver' => (string) config('mail.default'),

                'filesystem_disk' => (string) config('filesystems.default'),
                'storage_free_gb' => $storageFreeGb,
                'storage_writable' => $storageWritable,
            ],
            'diagnostics' => is_array($diagnostics) ? $diagnostics : null,
        ]);
    }

    public function diagnostics(Request $request): RedirectResponse
    {
        $results = [
            'db_ping_ms' => null,
            'cache_ping_ms' => null,
            'cache_ok' => null,
            'ran_at' => now()->toISOString(),
        ];

        try {
            $t0 = hrtime(true);
            DB::select('select 1');
            $t1 = hrtime(true);
            $results['db_ping_ms'] = round(($t1 - $t0) / 1_000_000, 2);
        } catch (\Throwable) {
            $results['db_ping_ms'] = null;
        }

        try {
            $key = 'perf_diag_'.bin2hex(random_bytes(8));
            $t0 = hrtime(true);
            Cache::put($key, '1', 10);
            $value = Cache::get($key);
            Cache::forget($key);
            $t1 = hrtime(true);
            $results['cache_ping_ms'] = round(($t1 - $t0) / 1_000_000, 2);
            $results['cache_ok'] = $value === '1';
        } catch (\Throwable) {
            $results['cache_ping_ms'] = null;
            $results['cache_ok'] = false;
        }

        $request->session()->put('performance_diagnostics', $results);

        return redirect()->route('admin.performance.index');
    }
}
