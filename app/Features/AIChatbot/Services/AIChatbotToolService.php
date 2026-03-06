<?php

namespace App\Features\AIChatbot\Services;

use App\Features\ActivityLogs\Models\ActivityLog;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Pds\Models\Pds;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AIChatbotToolService
{
    /**
     * Define the tools available to the Gemini AI based on the user's role.
     */
    public function getTools(User $user): array
    {
        $role = $user->role->value ?? $user->role;

        $tools = [
            $this->toolDeclaration(
                'get_active_announcements',
                'Retrieve the latest active company announcements and notices.'
            ),
        ];

        if ($role === UserRole::Employee->value || $role === UserRole::Hr->value || $role === UserRole::Admin->value) {
            $tools[] = $this->toolDeclaration(
                'get_my_leave_balances',
                'Retrieve the current user\'s available leave balances (Vacation, Sick, etc.).'
            );
            $tools[] = $this->toolDeclaration(
                'get_my_recent_applications',
                'Retrieve the current user\'s recent leave applications and training requests, including their approval status.'
            );
        }

        if ($role === UserRole::Admin->value || $role === UserRole::Hr->value) {
            $tools[] = $this->toolDeclaration(
                'get_system_statistics',
                'Retrieve high-level system statistics: total users, pending leave/training applications, and PDS statuses. ALWAYS use this when asked about counts or statistics.'
            );
            $tools[] = $this->toolDeclaration(
                'get_user_counts_by_role',
                'Retrieve user counts broken down by role (total, admins, HR, employees). Includes timestamp and applied filters.',
                []
            );
            $tools[] = $this->toolDeclaration(
                'get_user_count_for_role',
                'Retrieve user count for a single role. Role must be one of: admin, hr, employee. Includes timestamp and applied filters.',
                [
                    'role' => [
                        'type' => 'STRING',
                        'description' => 'Role to count (admin|hr|employee).',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_leave_application_details',
                'Retrieve detailed information about leave applications. Can filter by status and date range.',
                [
                    'status' => [
                        'type' => 'STRING',
                        'description' => 'Filter by status: pending, approved, rejected, or all.',
                    ],
                    'date_from' => [
                        'type' => 'STRING',
                        'description' => 'Start date filter (YYYY-MM-DD). Optional.',
                    ],
                    'date_to' => [
                        'type' => 'STRING',
                        'description' => 'End date filter (YYYY-MM-DD). Optional.',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_training_details',
                'Retrieve detailed information about training programs. Can filter by status.',
                [
                    'status' => [
                        'type' => 'STRING',
                        'description' => 'Filter by status: pending, approved, completed, or all.',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_employee_list',
                'Retrieve list of employees with filters for division, position, status. Can search by name. Use division, subdivision, or section for organizational unit queries.',
                [
                    'division' => [
                        'type' => 'STRING',
                        'description' => 'Filter by division/subdivision/section name. Optional.',
                    ],
                    'position' => [
                        'type' => 'STRING',
                        'description' => 'Filter by position title. Optional.',
                    ],
                    'status' => [
                        'type' => 'STRING',
                        'description' => 'Filter by employment status: active, inactive, or all. Default: active.',
                    ],
                    'search' => [
                        'type' => 'STRING',
                        'description' => 'Search by employee name or email. Optional.',
                    ],
                    'limit' => [
                        'type' => 'INTEGER',
                        'description' => 'Maximum number of results. Default: 50.',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_employee_by_id',
                'Retrieve detailed information about a specific employee by their ID or employee number.',
                [
                    'employee_id' => [
                        'type' => 'STRING',
                        'description' => 'Employee ID or employee number.',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_division_list',
                'Retrieve list of all divisions and subdivisions with employee counts.',
                []
            );
            $tools[] = $this->toolDeclaration(
                'get_position_list',
                'Retrieve list of all job positions with employee counts.',
                []
            );
            $tools[] = $this->toolDeclaration(
                'get_pds_summary',
                'Retrieve detailed PDS (Personal Data Sheet) statistics by status.',
                [
                    'status' => [
                        'type' => 'STRING',
                        'description' => 'Filter by status: draft, submitted, under_review, approved, rejected, or all.',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_leave_balance_summary',
                'Retrieve leave balance summary for all employees or by department.',
                [
                    'division' => [
                        'type' => 'STRING',
                        'description' => 'Filter by division name. Optional.',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_holiday_list',
                'Retrieve list of holidays for a year.',
                [
                    'year' => [
                        'type' => 'INTEGER',
                        'description' => 'Year to query. Defaults to current year.',
                    ],
                    'month' => [
                        'type' => 'INTEGER',
                        'description' => 'Filter by month (1-12). Optional.',
                    ],
                ]
            );
            $tools[] = $this->toolDeclaration(
                'get_leave_types_summary',
                'Retrieve summary of leave types used in applications.',
                [
                    'date_from' => [
                        'type' => 'STRING',
                        'description' => 'Start date filter (YYYY-MM-DD). Optional.',
                    ],
                    'date_to' => [
                        'type' => 'STRING',
                        'description' => 'End date filter (YYYY-MM-DD). Optional.',
                    ],
                ]
            );
        }

        if ($role === UserRole::Admin->value) {
            $tools[] = $this->toolDeclaration(
                'get_recent_system_activity',
                'Retrieve the 5 most recent system activity logs (audit trails).'
            );
        }

        return [
            ['functionDeclarations' => $tools],
        ];
    }

    private function toolDeclaration(string $name, string $description, array $properties = []): array
    {
        $decl = [
            'name' => $name,
            'description' => $description,
        ];

        if (! empty($properties)) {
            $decl['parameters'] = [
                'type' => 'OBJECT',
                'properties' => $properties,
            ];
        }

        return $decl;
    }

    /**
     * Execute a requested tool call.
     */
    public function handleToolCall(User $user, string $functionName, array $arguments): array
    {
        $role = $user->role->value ?? $user->role;

        try {
            return match ($functionName) {
                'get_active_announcements' => $this->getActiveAnnouncements(),
                'get_my_leave_balances' => $this->getMyLeaveBalances($user),
                'get_my_recent_applications' => $this->getMyRecentApplications($user),
                'get_system_statistics' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getSystemStatistics()
                    : ['error' => 'Unauthorized'],
                'get_user_counts_by_role' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getUserCountsByRole()
                    : ['error' => 'Unauthorized'],
                'get_user_count_for_role' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getUserCountForRole((string) ($arguments['role'] ?? ''))
                    : ['error' => 'Unauthorized'],
                'get_leave_application_details' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getLeaveApplicationDetails(
                        (string) ($arguments['status'] ?? 'all'),
                        (string) ($arguments['date_from'] ?? ''),
                        (string) ($arguments['date_to'] ?? '')
                    )
                    : ['error' => 'Unauthorized'],
                'get_training_details' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getTrainingDetails((string) ($arguments['status'] ?? 'all'))
                    : ['error' => 'Unauthorized'],
                'get_employee_list' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getEmployeeList(
                        (string) ($arguments['division'] ?? ''),
                        (string) ($arguments['position'] ?? ''),
                        (string) ($arguments['status'] ?? 'active'),
                        (string) ($arguments['search'] ?? ''),
                        (int) ($arguments['limit'] ?? 50)
                    )
                    : ['error' => 'Unauthorized'],
                'get_employee_by_id' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getEmployeeById((string) ($arguments['employee_id'] ?? ''))
                    : ['error' => 'Unauthorized'],
                'get_division_list' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getDivisionList()
                    : ['error' => 'Unauthorized'],
                'get_position_list' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getPositionList()
                    : ['error' => 'Unauthorized'],
                'get_pds_summary' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getPdsSummary((string) ($arguments['status'] ?? 'all'))
                    : ['error' => 'Unauthorized'],
                'get_leave_balance_summary' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getLeaveBalanceSummary((string) ($arguments['division'] ?? ''))
                    : ['error' => 'Unauthorized'],
                'get_holiday_list' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getHolidayList(
                        (int) ($arguments['year'] ?? now()->year),
                        (int) ($arguments['month'] ?? 0)
                    )
                    : ['error' => 'Unauthorized'],
                'get_leave_types_summary' => in_array($role, [UserRole::Admin->value, UserRole::Hr->value])
                    ? $this->getLeaveTypesSummary(
                        (string) ($arguments['date_from'] ?? ''),
                        (string) ($arguments['date_to'] ?? '')
                    )
                    : ['error' => 'Unauthorized'],
                'get_recent_system_activity' => $role === UserRole::Admin->value
                    ? $this->getRecentSystemActivity()
                    : ['error' => 'Unauthorized'],
                default => ['error' => "Unknown function: {$functionName}"],
            };
        } catch (\Throwable $e) {
            Log::error("AIChatbot Tool Error: {$functionName}", ['msg' => $e->getMessage()]);

            return ['error' => 'An error occurred while executing the tool.'];
        }
    }

    private function getActiveAnnouncements(): array
    {
        $posts = \App\Features\Posts\Models\Post::where('status', 'published')
            ->latest('published_at')
            ->limit(5)
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->title,
                    'excerpt' => $post->excerpt ?? mb_substr(strip_tags($post->content), 0, 100).'...',
                    'published_at' => $post->published_at?->format('Y-m-d'),
                ];
            });

        return ['active_announcements' => $posts->toArray()];
    }

    private function getMyLeaveBalances(User $user): array
    {
        $employee = $user->employee;
        if (! $employee) {
            return ['error' => 'User does not have an associated employee record.'];
        }

        $balances = $employee->leaveCredits->mapWithKeys(function ($credit) {
            return [$credit->leaveType->name => $credit->balance];
        });

        return ['leave_balances_in_days' => $balances->toArray()];
    }

    private function getMyRecentApplications(User $user): array
    {
        $employee = $user->employee;
        if (! $employee) {
            return ['error' => 'User does not have an associated employee record.'];
        }

        $leaves = LeaveApplication::where('employee_fk', $employee->id)
            ->latest('created_at')
            ->limit(3)
            ->get(['type', 'date_from', 'date_to', 'status'])
            ->toArray();

        $trainings = Training::where('employee_fk', $employee->id)
            ->latest('created_at')
            ->limit(3)
            ->get(['title', 'status', 'start_date'])
            ->toArray();

        return [
            'recent_leave_applications' => $leaves,
            'recent_training_applications' => $trainings,
        ];
    }

    private function getSystemStatistics(): array
    {
        $todayStr = now()->toDateString();

        // Single query for all user stats using conditional counting
        $userStats = DB::table('users')
            ->select([
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active'),
                DB::raw('SUM(CASE WHEN status = \'pending\' THEN 1 ELSE 0 END) as pending_approval'),
            ])
            ->first();

        // Single query for leave stats
        $leaveStats = DB::table('leave_applications')
            ->select([
                DB::raw('SUM(CASE WHEN DATE(created_at) = \''.$todayStr.'\' THEN 1 ELSE 0 END) as total_today'),
                DB::raw('SUM(CASE WHEN status = \'pending\' THEN 1 ELSE 0 END) as pending'),
            ])
            ->first();

        // Single query for training stats
        $trainingStats = DB::table('trainings')
            ->select([
                DB::raw('COUNT(*) as total_programs'),
                DB::raw('SUM(CASE WHEN status = \'pending\' THEN 1 ELSE 0 END) as pending_approval'),
            ])
            ->first();

        // Single query for PDS stats
        $pdsStats = DB::table('pds')
            ->select([
                DB::raw('COUNT(*) as total_submitted'),
                DB::raw('SUM(CASE WHEN status = \'draft\' THEN 1 ELSE 0 END) as draft'),
                DB::raw('SUM(CASE WHEN status IN (\'submitted\', \'under_review\') THEN 1 ELSE 0 END) as pending_review'),
            ])
            ->first();

        return [
            'users' => [
                'total' => (int) $userStats->total,
                'active' => (int) $userStats->active,
                'pending_approval' => (int) $userStats->pending_approval,
            ],
            'leave_applications' => [
                'total_today' => (int) $leaveStats->total_today,
                'pending' => (int) $leaveStats->pending,
            ],
            'training' => [
                'total_programs' => (int) $trainingStats->total_programs,
                'pending_approval' => (int) $trainingStats->pending_approval,
            ],
            'pds' => [
                'total_submitted' => (int) $pdsStats->total_submitted,
                'draft' => (int) $pdsStats->draft,
                'pending_review' => (int) $pdsStats->pending_review,
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getUserCountsByRole(): array
    {
        if (! Schema::hasTable('users')) {
            return ['error' => 'Users table is not available.'];
        }

        $cacheKey = 'ai_chatbot:tool:user_counts_by_role';
        $cacheSeconds = 60;

        return Cache::remember($cacheKey, $cacheSeconds, function () {
            $counts = $this->usersBaseQuery()
                ->selectRaw('COUNT(*) as total_users')
                ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as admins', [UserRole::Admin->value])
                ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as hr_staff', [UserRole::Hr->value])
                ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as employees', [UserRole::Employee->value])
                ->first();

            $data = [
                'total_users' => (int) ($counts->total_users ?? 0),
                'admins' => (int) ($counts->admins ?? 0),
                'hr_staff' => (int) ($counts->hr_staff ?? 0),
                'employees' => (int) ($counts->employees ?? 0),
            ];

            $sum = $data['admins'] + $data['hr_staff'] + $data['employees'];

            return [
                'data' => $data,
                'meta' => [
                    'as_of' => now()->toDateTimeString(),
                    'filters' => $this->usersVisibilityFilters(),
                    'definitions' => [
                        'total_users' => 'All user accounts in users table (admins + HR + employees).',
                        'employees' => 'Users with role=employee (not necessarily joined to employees table).',
                    ],
                    'validation' => [
                        'sum_roles' => $sum,
                        'total_matches_sum_roles' => $data['total_users'] === $sum,
                    ],
                ],
            ];
        });
    }

    private function getUserCountForRole(string $role): array
    {
        if (! Schema::hasTable('users')) {
            return ['error' => 'Users table is not available.'];
        }

        $role = mb_strtolower(trim($role));
        $allowed = [UserRole::Admin->value, UserRole::Hr->value, UserRole::Employee->value];
        if (! in_array($role, $allowed, true)) {
            return ['error' => 'Invalid role. Allowed: admin, hr, employee.'];
        }

        $cacheKey = 'ai_chatbot:tool:user_count_role:'.$role;
        $cacheSeconds = 60;

        return Cache::remember($cacheKey, $cacheSeconds, function () use ($role) {
            $count = (int) $this->usersBaseQuery()
                ->where('role', $role)
                ->count();

            return [
                'data' => [
                    'role' => $role,
                    'count' => $count,
                ],
                'meta' => [
                    'as_of' => now()->toDateTimeString(),
                    'filters' => array_merge($this->usersVisibilityFilters(), ['users.role = '.$role]),
                ],
            ];
        });
    }

    private function getRecentSystemActivity(): array
    {
        $logs = ActivityLog::with('actor')->latest('created_at')->limit(5)->get()->map(function ($log) {
            $actorName = $log->actor ? trim($log->actor->first_name.' '.$log->actor->last_name) : 'System';

            return [
                'actor' => $actorName,
                'action' => $log->action,
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'date' => $log->created_at?->format('Y-m-d H:i'),
            ];
        });

        return ['recent_activity' => $logs->toArray()];
    }

    private function getEmployeeList(string $division = '', string $position = '', string $status = 'active', string $search = '', int $limit = 50): array
    {
        if (! Schema::hasTable('employees')) {
            return ['error' => 'Employees table is not available.'];
        }

        $query = DB::table('employees')
            ->select([
                'employees.id',
                'employees.first_name',
                'employees.middle_name',
                'employees.last_name',
                'employees.email',
                'employees.division',
                'employees.subdivision',
                'employees.section',
                'employees.position',
                'employees.classification',
                'employees.date_hired',
                'employees.status',
            ]);

        if (Schema::hasTable('users')) {
            $query->leftJoin('users', 'users.id', '=', 'employees.user_id')
                ->addSelect('users.role');
        }

        if ($status !== 'all') {
            $query->where('employees.status', $status);
        }

        if ($division !== '') {
            $query->where(function ($q) use ($division) {
                $q->where('employees.division', 'like', '%'.$division.'%')
                    ->orWhere('employees.subdivision', 'like', '%'.$division.'%')
                    ->orWhere('employees.section', 'like', '%'.$division.'%');
            });
        }

        if ($position !== '') {
            $query->where('employees.position', 'like', '%'.$position.'%');
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('employees.first_name', 'like', '%'.$search.'%')
                    ->orWhere('employees.last_name', 'like', '%'.$search.'%')
                    ->orWhere('employees.email', 'like', '%'.$search.'%')
                    ->orWhere('employees.id', $search);
            });
        }

        $employees = $query->limit($limit)->get();

        $byDivision = [];
        $byPosition = [];
        $byStatus = ['active' => 0, 'inactive' => 0];

        foreach ($employees as $emp) {
            $div = $emp->division ?? 'Unassigned';
            $byDivision[$div] = ($byDivision[$div] ?? 0) + 1;

            $pos = $emp->position ?? 'Unassigned';
            $byPosition[$pos] = ($byPosition[$pos] ?? 0) + 1;

            $stat = $emp->status ?? 'inactive';
            if (isset($byStatus[$stat])) {
                $byStatus[$stat]++;
            }
        }

        return [
            'employees' => $employees->toArray(),
            'summary' => [
                'total' => $employees->count(),
                'by_division' => $byDivision,
                'by_position' => $byPosition,
                'by_status' => $byStatus,
                'filters_applied' => [
                    'division' => $division ?: null,
                    'position' => $position ?: null,
                    'status' => $status,
                    'search' => $search ?: null,
                ],
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getTrainingDetails(string $status = 'all'): array
    {
        if (! Schema::hasTable('trainings')) {
            return ['error' => 'Trainings table is not available.'];
        }

        $query = DB::table('trainings')
            ->select([
                'id',
                'employee_id',
                'title',
                'status',
                'date_from',
                'date_to',
                'created_at',
            ]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $trainings = $query->latest('created_at')->limit(50)->get();

        $byStatus = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        foreach ($trainings as $training) {
            if (isset($byStatus[$training->status])) {
                $byStatus[$training->status]++;
            }
        }

        return [
            'trainings' => $trainings->toArray(),
            'summary' => [
                'total' => $trainings->count(),
                'by_status' => $byStatus,
                'filter_applied' => $status,
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getLeaveApplicationDetails(string $status = 'all', string $dateFrom = '', string $dateTo = ''): array
    {
        if (! Schema::hasTable('leave_applications')) {
            return ['error' => 'Leave applications table is not available.'];
        }

        $query = DB::table('leave_applications')
            ->select([
                'id',
                'employee_id',
                'type',
                'status',
                'date_from',
                'date_to',
                'total_days',
                'created_at',
            ]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($dateFrom !== '') {
            $query->where('date_from', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->where('date_to', '<=', $dateTo);
        }

        $applications = $query->latest('created_at')->limit(50)->get();

        $byStatus = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        $byType = [];

        foreach ($applications as $app) {
            if (isset($byStatus[$app->status])) {
                $byStatus[$app->status]++;
            }
            $type = $app->type ?? 'Other';
            $byType[$type] = ($byType[$type] ?? 0) + 1;
        }

        return [
            'applications' => $applications->toArray(),
            'summary' => [
                'total' => $applications->count(),
                'by_status' => $byStatus,
                'by_type' => $byType,
                'filters' => [
                    'status' => $status,
                    'date_from' => $dateFrom ?: null,
                    'date_to' => $dateTo ?: null,
                ],
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getEmployeeById(string $employeeId): array
    {
        if (! Schema::hasTable('employees')) {
            return ['error' => 'Employees table is not available.'];
        }

        $employee = DB::table('employees')
            ->where('employees.id', $employeeId)
            ->first();

        if (! $employee) {
            return ['error' => 'Employee not found.'];
        }

        $leaveBalances = [];
        $recentLeaves = [];
        $recentTrainings = [];

        if (Schema::hasTable('leave_credits')) {
            $leaveBalances = DB::table('leave_credits')
                ->where('leave_credits.employee_id', $employee->id)
                ->select(['leave_type', 'balance'])
                ->get()
                ->toArray();
        }

        if (Schema::hasTable('leave_applications')) {
            $recentLeaves = DB::table('leave_applications')
                ->where('employee_id', $employee->id)
                ->latest('created_at')
                ->limit(5)
                ->get(['type', 'status', 'date_from', 'date_to', 'created_at'])
                ->toArray();
        }

        if (Schema::hasTable('trainings')) {
            $recentTrainings = DB::table('trainings')
                ->where('employee_id', $employee->id)
                ->latest('created_at')
                ->limit(5)
                ->get(['title', 'status', 'start_date', 'end_date', 'created_at'])
                ->toArray();
        }

        return [
            'employee' => (array) $employee,
            'leave_balances' => $leaveBalances,
            'recent_leaves' => $recentLeaves,
            'recent_trainings' => $recentTrainings,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getDivisionList(): array
    {
        if (! Schema::hasTable('employees')) {
            return ['error' => 'Employees table is not available.'];
        }

        $divisions = DB::table('employees')
            ->select('division', DB::raw('COUNT(*) as employee_count'))
            ->whereNotNull('division')
            ->where('division', '!=', '')
            ->groupBy('division')
            ->orderBy('division')
            ->get();

        $subdivisions = DB::table('employees')
            ->select('subdivision', DB::raw('COUNT(*) as employee_count'))
            ->whereNotNull('subdivision')
            ->where('subdivision', '!=', '')
            ->groupBy('subdivision')
            ->orderBy('subdivision')
            ->get();

        $totalEmployees = DB::table('employees')->count();

        return [
            'divisions' => $divisions->toArray(),
            'subdivisions' => $subdivisions->toArray(),
            'summary' => [
                'total_divisions' => $divisions->count(),
                'total_subdivisions' => $subdivisions->count(),
                'total_employees' => $totalEmployees,
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getPositionList(): array
    {
        if (! Schema::hasTable('employees')) {
            return ['error' => 'Employees table is not available.'];
        }

        $positions = DB::table('employees')
            ->select('position', DB::raw('COUNT(*) as employee_count'))
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->groupBy('position')
            ->orderBy('position')
            ->get();

        return [
            'positions' => $positions->toArray(),
            'summary' => [
                'total_positions' => $positions->count(),
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getPdsSummary(string $status = 'all'): array
    {
        if (! Schema::hasTable('pds')) {
            return ['error' => 'PDS table is not available.'];
        }

        $query = DB::table('pds')
            ->select([
                'id',
                'employee_id',
                'status',
                'created_at',
                'updated_at',
            ]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $pdsRecords = $query->latest('updated_at')->limit(100)->get();

        $byStatus = [
            'draft' => 0,
            'submitted' => 0,
            'under_review' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        foreach ($pdsRecords as $pds) {
            if (isset($byStatus[$pds->status])) {
                $byStatus[$pds->status]++;
            }
        }

        return [
            'pds_records' => $pdsRecords->toArray(),
            'summary' => [
                'total' => $pdsRecords->count(),
                'by_status' => $byStatus,
                'filter_applied' => $status,
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getLeaveBalanceSummary(string $division = ''): array
    {
        if (! Schema::hasTable('leave_credits') || ! Schema::hasTable('employees')) {
            return ['error' => 'Leave credits or employees table is not available.'];
        }

        $query = DB::table('leave_credits')
            ->join('employees', 'employees.id', '=', 'leave_credits.employee_id')
            ->select([
                'leave_credits.leave_type',
                DB::raw('SUM(leave_credits.balance) as total_balance'),
                DB::raw('COUNT(*) as employee_count'),
            ]);

        if ($division !== '') {
            $query->where(function ($q) use ($division) {
                $q->where('employees.division', 'like', '%'.$division.'%')
                    ->orWhere('employees.subdivision', 'like', '%'.$division.'%');
            });
        }

        $summary = $query->groupBy('leave_credits.leave_type')
            ->orderBy('leave_credits.leave_type')
            ->get();

        return [
            'summary' => $summary->toArray(),
            'filter_applied' => $division ?: null,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getHolidayList(int $year = 0, int $month = 0): array
    {
        if ($year === 0) {
            $year = now()->year;
        }

        $query = DB::table('custom_holidays')
            ->select(['id', 'title', 'date', 'category', 'description', 'is_recurring'])
            ->whereYear('date', $year);

        if ($month > 0 && $month <= 12) {
            $query->whereMonth('date', $month);
        }

        $holidays = $query->orderBy('date')->get();

        $regularHolidays = [];
        if (Schema::hasTable('holidays')) {
            $regularQuery = DB::table('holidays')
                ->select(['id', 'name as title', 'date', 'type as category'])
                ->whereYear('date', $year);

            if ($month > 0 && $month <= 12) {
                $regularQuery->whereMonth('date', $month);
            }

            $regularHolidays = $regularQuery->orderBy('date')->get()->toArray();
        }

        return [
            'custom_holidays' => $holidays->toArray(),
            'regular_holidays' => $regularHolidays,
            'summary' => [
                'total_custom' => $holidays->count(),
                'total_regular' => count($regularHolidays),
                'year' => $year,
                'month' => $month > 0 ? $month : null,
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    private function getLeaveTypesSummary(string $dateFrom = '', string $dateTo = ''): array
    {
        if (! Schema::hasTable('leave_applications')) {
            return ['error' => 'Leave applications table is not available.'];
        }

        $query = DB::table('leave_applications')
            ->select([
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = \'pending\' THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = \'approved\' THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = \'rejected\' THEN 1 ELSE 0 END) as rejected'),
            ]);

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $summary = $query->groupBy('type')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'leave_types_summary' => $summary->toArray(),
            'filters_applied' => [
                'date_from' => $dateFrom ?: null,
                'date_to' => $dateTo ?: null,
            ],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get context stats for the system prompt, in the format expected by getSystemPrompt.
     * NO CACHING - always returns real-time data for maximum accuracy.
     */
    public function getContextStatsForRole(User $user): array
    {
        $role = (string) $user->role;

        if ($role === UserRole::Hr->value) {
            return $this->buildHrStats();
        }
        if ($role === UserRole::Admin->value) {
            return $this->buildAdminStats();
        }

        return [];
    }

    private function buildHrStats(): array
    {
        $empty = [
            'users' => [
                'total' => 0,
                'active' => 0,
                'pending_approval' => 0,
                'hr_personnel' => 0,
                'employees' => 0,
            ],
            'leave_applications' => [
                'total_today' => 0,
                'pending' => 0,
                'approved_this_month' => 0,
                'recent_list' => 'No recent leave applications.',
            ],
            'training' => [
                'total_programs' => 0,
                'pending_approval' => 0,
                'approved' => 0,
                'recent_list' => 'No recent training records.',
            ],
            'pds' => [
                'total_submitted' => 0,
                'draft' => 0,
                'pending_review' => 0,
                'approved' => 0,
                'rejected' => 0,
            ],
        ];

        if (! Schema::hasTable('users')) {
            return $empty;
        }

        $query = $this->usersBaseQuery()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as hr_personnel', [UserRole::Hr->value])
            ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as employees', [UserRole::Employee->value]);
        if (Schema::hasColumn('users', 'is_active')) {
            $query->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active');
        } else {
            $query->selectRaw('COUNT(*) as active');
        }
        if (Schema::hasColumn('users', 'status')) {
            $query->selectRaw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_approval");
        } else {
            $query->selectRaw('0 as pending_approval');
        }
        $userCounts = $query->first();

        $hrCount = (int) ($userCounts->hr_personnel ?? 0);
        $empCount = (int) ($userCounts->employees ?? 0);
        $empty['users'] = [
            'total' => $hrCount + $empCount,
            'active' => (int) ($userCounts->active ?? 0),
            'pending_approval' => (int) ($userCounts->pending_approval ?? 0),
            'hr_personnel' => $hrCount,
            'employees' => $empCount,
        ];

        if (Schema::hasTable('leave_applications')) {
            $today = now()->startOfDay()->toDateString();
            $leavePending = (int) DB::table('leave_applications')->where('status', 'pending')->count();
            $leaveToday = (int) DB::table('leave_applications')->whereDate('created_at', $today)->count();
            $leaveApprovedMonth = (int) DB::table('leave_applications')
                ->where('status', 'approved')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $recentLeaves = LeaveApplication::with('employee')
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn ($la) => '- '.($la->employee?->user?->name ?? $la->employee_name ?? 'Unknown').' ('.$la->type.', '.$la->status.', '.($la->date_from?->format('Y-m-d') ?? '').')')
                ->implode("\n") ?: 'No recent leave applications.';

            $empty['leave_applications'] = [
                'total_today' => $leaveToday,
                'pending' => $leavePending,
                'approved_this_month' => $leaveApprovedMonth,
                'recent_list' => $recentLeaves,
            ];
        }

        if (Schema::hasTable('trainings')) {
            $trainingTotal = (int) DB::table('trainings')->count();
            $trainingPending = (int) DB::table('trainings')->where('status', 'pending')->count();
            $trainingApproved = (int) DB::table('trainings')->where('status', 'approved')->count();
            $recentTrainings = Training::with('employee')
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn ($t) => '- '.($t->employee?->user?->name ?? $t->employee_name ?? 'Unknown').' ('.$t->title.', '.$t->status.', '.($t->date_from?->format('Y-m-d') ?? '').')')
                ->implode("\n") ?: 'No recent training records.';

            $empty['training'] = [
                'total_programs' => $trainingTotal,
                'pending_approval' => $trainingPending,
                'approved' => $trainingApproved,
                'recent_list' => $recentTrainings,
            ];
        }

        if (Schema::hasTable('pds')) {
            $pdsSubmitted = (int) DB::table('pds')->count();
            $pdsDraft = (int) DB::table('pds')->where('status', 'draft')->count();
            $pdsPending = (int) DB::table('pds')->whereIn('status', ['submitted', 'under_review'])->count();
            $pdsApproved = (int) DB::table('pds')->where('status', 'approved')->count();
            $pdsRejected = (int) DB::table('pds')->where('status', 'rejected')->count();

            $empty['pds'] = [
                'total_submitted' => $pdsSubmitted,
                'draft' => $pdsDraft,
                'pending_review' => $pdsPending,
                'approved' => $pdsApproved,
                'rejected' => $pdsRejected,
            ];
        }

        return $empty;
    }

    private function buildAdminStats(): array
    {
        $empty = [
            'total_users' => 0,
            'admins' => 0,
            'hr_staff' => 0,
            'employees' => 0,
            'active_users_today' => 0,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'recent_activity' => 'No recent activity logs.',
        ];

        if (! Schema::hasTable('users')) {
            return $empty;
        }

        $userCounts = $this->usersBaseQuery()
            ->selectRaw('COUNT(*) as total_users')
            ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as admins', [UserRole::Admin->value])
            ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as hr_staff', [UserRole::Hr->value])
            ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as employees', [UserRole::Employee->value])
            ->first();

        $empty['total_users'] = (int) ($userCounts->total_users ?? 0);
        $empty['admins'] = (int) ($userCounts->admins ?? 0);
        $empty['hr_staff'] = (int) ($userCounts->hr_staff ?? 0);
        $empty['employees'] = (int) ($userCounts->employees ?? 0);

        if (Schema::hasColumn('users', 'last_login_at')) {
            $today = now()->startOfDay();
            $empty['active_users_today'] = (int) DB::table('users')->where('last_login_at', '>=', $today)->count();
        }

        if (Schema::hasTable('activity_logs')) {
            $logs = ActivityLog::with('actor')->latest('created_at')->limit(5)->get()
                ->map(fn ($log) => '- '.($log->created_at?->format('Y-m-d H:i') ?? '').' — '.($log->actor ? trim($log->actor->first_name.' '.$log->actor->last_name) : 'System').': '.$log->action)
                ->implode("\n") ?: 'No recent activity logs.';
            $empty['recent_activity'] = $logs;
        }

        return $empty;
    }

    private function usersBaseQuery(): \Illuminate\Database\Query\Builder
    {
        $query = DB::table('users');
        if (Schema::hasColumn('users', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        return $query;
    }

    private function usersVisibilityFilters(): array
    {
        return Schema::hasColumn('users', 'deleted_at')
            ? ['users.deleted_at IS NULL']
            : [];
    }
}
