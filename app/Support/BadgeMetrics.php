<?php

namespace App\Support;

use App\Features\Leave\Models\LeaveApplication;
use App\Features\Notices\Models\Notice;
use App\Features\Pds\Models\Pds;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class BadgeMetrics
{
    /**
     * Get badge counts for the given user, with short-lived caching.
     */
    public static function forUser(?User $user): array
    {
        if (! $user) {
            return [];
        }

        $role = $user->getRoleEnum() ?? UserRole::Employee;

        return Cache::remember(
            self::badgeCacheKey($user->id, $role->value),
            now()->addSeconds(60),
            function () use ($user, $role): array {
                $counts = [];

                if ($role === UserRole::Admin) {
                    $counts['users_pending'] = User::where('status', 'pending')->count();
                    $counts['notices_unread'] = self::unreadNoticesForUser($user);
                }

                if ($role === UserRole::Hr) {
                    $usersPendingQuery = User::where('status', 'pending')
                        ->where('role', '!=', UserRole::Admin->value);

                    $leaveQuery = LeaveApplication::where('status', 'pending')
                        ->whereHas('employee.user', function ($q): void {
                            $q->where('role', '!=', UserRole::Admin->value);
                        });

                    $trainingQuery = Training::where('status', 'assigned')
                        ->whereHas('employee.user', function ($q): void {
                            $q->where('role', '!=', UserRole::Admin->value);
                        });

                    $pdsQuery = Pds::where('status', 'pending')
                        ->whereHas('employee.user', function ($q): void {
                            $q->where('role', '!=', UserRole::Admin->value);
                        });

                    $counts['leaves_pending'] = $leaveQuery->count();
                    $counts['trainings_assigned'] = $trainingQuery->count();
                    $counts['pds_pending'] = $pdsQuery->count();
                    $counts['users_pending'] = $usersPendingQuery->count();
                    $counts['notices_unread'] = self::unreadNoticesForUser($user);
                }

                if ($role === UserRole::Employee) {
                    $employeeId = $user->employee?->id;

                    $counts['leaves_pending'] = $employeeId
                        ? LeaveApplication::where('employee_id', $employeeId)
                            ->where('status', 'pending')
                            ->count()
                        : 0;

                    $counts['trainings_assigned'] = $employeeId
                        ? Training::where('employee_id', $employeeId)
                            ->where('status', 'assigned')
                            ->count()
                        : 0;

                    $counts['notices_unread'] = self::unreadNoticesForUser($user);
                }

                return $counts;
            }
        );
    }

    private static function unreadNoticesForUser(User $user): int
    {
        try {
            return Notice::active()
                ->whereDoesntHave('readers', function ($q) use ($user): void {
                    $q->where('user_id', $user->id);
                })
                ->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private static function badgeCacheKey(int $userId, string $role): string
    {
        return "badges:{$userId}:{$role}";
    }
}
