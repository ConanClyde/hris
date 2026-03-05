<?php

namespace App\Providers;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Policies\LeavePolicy;
use App\Features\Notices\Models\Notice;
use App\Features\Notices\Policies\NoticePolicy;
use App\Features\Users\Enums\UserRole;
use App\Features\Users\Policies\UserPolicy;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
        $this->registerPolicies();
        $this->registerAuthActivityLogging();
    }

    protected function registerPolicies(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(LeaveApplication::class, LeavePolicy::class);
        Gate::policy(Notice::class, NoticePolicy::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    /**
     * Configure rate limiting for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('chatbot', function (Request $request) {
            $role = (string) ($request->user()?->role ?? '');
            $limit = match ($role) {
                UserRole::Admin->value => 60,
                UserRole::Hr->value => 40,
                UserRole::Employee->value => 25,
                default => 10,
            };

            return Limit::perMinute($limit)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function registerAuthActivityLogging(): void
    {
        Event::listen(Login::class, function (Login $event): void {
            $user = $event->user;
            if (! $user) {
                return;
            }

            app(ActivityLogger::class)->log(
                action: 'login',
                actorUserId: (int) $user->getAuthIdentifier(),
                role: $user->role ?? null,
                subjectType: class_basename($user),
                subjectId: is_numeric($user->getAuthIdentifier()) ? (int) $user->getAuthIdentifier() : null,
                metadata: [
                    'guard' => $event->guard,
                    'email' => $user->email ?? null,
                ],
            );
        });

        Event::listen(Logout::class, function (Logout $event): void {
            $user = $event->user;
            if (! $user) {
                return;
            }

            app(ActivityLogger::class)->log(
                action: 'logout',
                actorUserId: (int) $user->getAuthIdentifier(),
                role: $user->role ?? null,
                subjectType: class_basename($user),
                subjectId: is_numeric($user->getAuthIdentifier()) ? (int) $user->getAuthIdentifier() : null,
                metadata: [
                    'guard' => $event->guard,
                    'email' => $user->email ?? null,
                ],
            );
        });
    }
}
