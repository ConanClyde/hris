<?php

namespace App\Providers;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Policies\LeavePolicy;
use App\Features\Notices\Models\Notice;
use App\Features\Notices\Policies\NoticePolicy;
use App\Features\Users\Models\User;
use App\Features\Users\Policies\UserPolicy;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ActivityLogger::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register authorization policies
        Gate::policy(LeaveApplication::class, LeavePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Notice::class, NoticePolicy::class);

        if (app()->environment('local')) {
            $this->registerPerformanceLogging();
        }

        View::composer(['layouts.dashboard', 'layouts.pds'], function ($view) {
            $globalNotices = collect();

            if (session('user_id')) {
                $globalNotices = Cache::remember('global_notices_active', 60, function () {
                    return Notice::active()
                        ->latest()
                        ->get();
                });
            }

            $view->with('globalNotices', $globalNotices);
        });

        View::composer('partials.sidebar', function ($view) {
            $sidebarProfile = [
                'display_name' => 'User',
                'email' => '',
                'initial' => 'U',
                'avatar_url' => null,
            ];

            if (session('user_id')) {
                $userId = (int) session('user_id');
                $cacheKey = 'sidebar_profile_'.$userId;

                $sidebarProfile = Cache::remember($cacheKey, 60, function () use ($userId) {
                    $sidebarProfile = [
                        'display_name' => 'User',
                        'email' => '',
                        'initial' => 'U',
                        'avatar_url' => null,
                    ];

                    $user = Auth::user() ?? User::with('employee')->find($userId);
                    $email = $user?->email ?? '';
                    $displayName = null;

                    if ($user) {
                        $emp = $user->employee;
                        if ($emp && trim($emp->first_name.' '.$emp->last_name)) {
                            $displayName = trim($emp->first_name.' '.$emp->last_name);
                        }
                        if (! $displayName) {
                            $displayName = $user->display_name ?: ($user->name ?: ($user->user_id ?: null));
                        }
                    }

                    if (! $displayName) {
                        $displayName = $email ?: 'User';
                    }

                    $avatarPath = session('avatar');
                    $sidebarProfile = [
                        'display_name' => $displayName,
                        'email' => $email ?: '',
                        'initial' => strtoupper(mb_substr($displayName, 0, 1)) ?: 'U',
                        'avatar_url' => $avatarPath ? asset('storage/'.$avatarPath) : null,
                    ];

                    return $sidebarProfile;
                });
            }

            $view->with('sidebarProfile', $sidebarProfile);
        });
    }

    private function registerPerformanceLogging(): void
    {
        app()->singleton('perf.querylog', function () {
            return [
                'count' => 0,
                'total_ms' => 0.0,
                'slow' => [],
            ];
        });

        DB::listen(function (QueryExecuted $query): void {
            $state = app('perf.querylog');

            $state['count']++;
            $state['total_ms'] += (float) $query->time;

            $state['slow'][] = [
                'time_ms' => (float) $query->time,
                'sql' => $query->sql,
                'connection' => $query->connectionName,
            ];

            if (count($state['slow']) > 10) {
                usort($state['slow'], fn ($a, $b) => $b['time_ms'] <=> $a['time_ms']);
                $state['slow'] = array_slice($state['slow'], 0, 10);
            }

            app()->instance('perf.querylog', $state);
        });

        app('events')->listen('kernel.handled', function ($request, $response): void {
            $state = app('perf.querylog');

            $durationMs = null;
            if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
                $durationMs = (microtime(true) - (float) $_SERVER['REQUEST_TIME_FLOAT']) * 1000;
            }

            $route = null;
            try {
                $route = optional($request->route())->getName() ?: optional($request->route())->uri();
            } catch (\Throwable $e) {
                $route = null;
            }

            Log::info('PERF request', [
                'method' => $request->getMethod(),
                'path' => $request->getPathInfo(),
                'route' => $route,
                'status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null,
                'duration_ms' => $durationMs,
                'db_query_count' => $state['count'] ?? 0,
                'db_total_ms' => $state['total_ms'] ?? 0,
                'slow_queries' => $state['slow'] ?? [],
            ]);

            if (method_exists($response, 'headers')) {
                $serverTiming = [];
                if ($durationMs !== null) {
                    $serverTiming[] = sprintf('app;dur=%.1f', $durationMs);
                }
                $serverTiming[] = sprintf('db;dur=%.1f', (float) ($state['total_ms'] ?? 0));
                $serverTiming[] = sprintf('dbq;desc="queries";dur=%d', (int) ($state['count'] ?? 0));
                $response->headers->set('Server-Timing', implode(', ', $serverTiming));
            }

            app()->instance('perf.querylog', [
                'count' => 0,
                'total_ms' => 0.0,
                'slow' => [],
            ]);
        });
    }
}
