<?php

namespace App\Features\AIChatbot\Services;

use App\Features\ActivityLogs\Models\ActivityLog;
use App\Features\Calendar\Models\CustomHoliday;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use App\Features\Posts\Models\Post;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AIChatbotSuggestionService
{
    private const FUZZY_MATCH_MIN_SCORE = 0.72;

    public function suggestionsForUser(User $user): array
    {
        $role = (string) $user->role;

        return array_values(array_map(function (array $item): array {
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'icon' => $item['icon'],
            ];
        }, array_filter($this->definitions(), function (array $item) use ($role): bool {
            if (! in_array($role, $item['roles'], true)) {
                return false;
            }
            // Hide follow-up-only suggestions from the initial screen
            if (! empty($item['followup_only'])) {
                return false;
            }

            return true;
        })));
    }

    public function recentIntents(User $user): array
    {
        $key = 'ai_chatbot:recent_intents:'.$user->id;
        $recent = Cache::get($key, []);

        return is_array($recent) ? array_values(array_filter($recent, fn ($id) => is_string($id) && $id !== '')) : [];
    }

    public function rememberIntent(User $user, string $intentId): array
    {
        $key = 'ai_chatbot:recent_intents:'.$user->id;
        $recent = $this->recentIntents($user);
        $recent = array_values(array_unique(array_merge([$intentId], $recent)));
        $recent = array_slice($recent, 0, 5);
        Cache::put($key, $recent, now()->addMinutes(30));

        return $recent;
    }

    public function followUpSuggestions(User $user, ?string $excludeId = null, array $recentIntents = []): array
    {
        $suggestions = $this->suggestionsForUser($user);
        if ($excludeId) {
            $suggestions = array_values(array_filter($suggestions, fn (array $item) => $item['id'] !== $excludeId));
        }

        $priorityMap = $this->priorityMap();
        $recentPrefixes = array_values(array_unique(array_map(function (string $id): string {
            $parts = explode('_', $id);

            return $parts[0] ?? $id;
        }, array_filter($recentIntents, fn ($id) => is_string($id) && $id !== ''))));

        usort($suggestions, function (array $a, array $b) use ($recentPrefixes, $priorityMap): int {
            $aPrefix = explode('_', (string) ($a['id'] ?? ''))[0] ?? '';
            $bPrefix = explode('_', (string) ($b['id'] ?? ''))[0] ?? '';
            $aRank = in_array($aPrefix, $recentPrefixes, true) ? 0 : 1;
            $bRank = in_array($bPrefix, $recentPrefixes, true) ? 0 : 1;
            if ($aRank !== $bRank) {
                return $aRank <=> $bRank;
            }

            $aPriority = (int) ($priorityMap[$a['id']] ?? 0);
            $bPriority = (int) ($priorityMap[$b['id']] ?? 0);
            if ($aPriority !== $bPriority) {
                return $bPriority <=> $aPriority;
            }

            return strcmp((string) $a['title'], (string) $b['title']);
        });

        return array_slice($suggestions, 0, 3);
    }

    private function priorityMap(): array
    {
        $map = [];
        foreach ($this->definitions() as $definition) {
            $map[$definition['id']] = (int) ($definition['priority'] ?? 0);
        }

        return $map;
    }

    public function normalizeQuestion(string $question): string
    {
        $normalized = trim(mb_strtolower($question));
        $normalized = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        return trim((string) $normalized);
    }

    /**
     * Detect if a question is compound (contains multiple questions).
     */
    public function isCompoundQuestion(string $question): bool
    {
        $normalized = mb_strtolower($question);

        // Pattern 1: Multiple question marks
        $questionCount = substr_count($normalized, '?');
        if ($questionCount >= 2) {
            return true;
        }

        // Pattern 2: "and" connecting different topic keywords
        $compoundPatterns = [
            '/how many.*and.*how many/i',
            '/what.*and.*what/i',
            '/show.*and.*show/i',
            '/list.*and.*list/i',
            '/employees.*hr.*admin/i',
            '/users.*and.*employees/i',
            '/leave.*and.*training/i',
            '/announcements.*and.*holidays/i',
            '/balance.*and.*pending/i',
            '/how many.*employees.*how many.*hr/i',
            '/how many.*hr.*how many.*admin/i',
        ];

        foreach ($compoundPatterns as $pattern) {
            if (preg_match($pattern, $normalized)) {
                return true;
            }
        }

        // Pattern 3: Comma-separated questions
        if (preg_match('/\?\s*,\s*(how|what|show|list|tell|are there)/i', $question)) {
            return true;
        }

        return false;
    }

    public function matchSuggestion(User $user, string $question): ?array
    {
        $normalizedQuestion = $this->normalizeQuestion($question);
        if ($normalizedQuestion === '') {
            return null;
        }

        $role = (string) $user->role;

        foreach ($this->definitions() as $definition) {
            if (! in_array($role, $definition['roles'], true)) {
                continue;
            }

            if ($this->normalizeQuestion($definition['title']) === $normalizedQuestion) {
                return [
                    'id' => $definition['id'],
                    'match' => 'title',
                ];
            }

            $aliases = $definition['aliases'] ?? [];
            foreach ($aliases as $alias) {
                if ($this->normalizeQuestion($alias) === $normalizedQuestion) {
                    return [
                        'id' => $definition['id'],
                        'match' => 'alias',
                    ];
                }
            }
        }

        $best = null;
        foreach ($this->definitions() as $definition) {
            if (! in_array($role, $definition['roles'], true)) {
                continue;
            }

            $candidates = array_merge([$definition['title']], $definition['aliases'] ?? []);
            foreach ($candidates as $candidate) {
                $candidateNorm = $this->normalizeQuestion((string) $candidate);
                if ($candidateNorm === '') {
                    continue;
                }

                // Guard against very short queries matching inside long candidates
                // e.g. "hi" matching "holidays this month" via the "hi" in "this"
                $qLen = mb_strlen($normalizedQuestion);
                $cLen = mb_strlen($candidateNorm);
                if ($qLen >= 4 && $cLen >= 4) {
                    $isSubstringMatch = false;
                    if (str_contains($normalizedQuestion, $candidateNorm)) {
                        $isSubstringMatch = true;
                    } elseif (str_contains($candidateNorm, $normalizedQuestion) && $qLen >= ($cLen * 0.4)) {
                        $isSubstringMatch = true;
                    }
                    if ($isSubstringMatch) {
                        return [
                            'id' => $definition['id'],
                            'match' => 'contains',
                        ];
                    }
                }

                if ($qLen >= 4) {
                    $score = $this->similarityScore($normalizedQuestion, $candidateNorm);
                    if ($score >= self::FUZZY_MATCH_MIN_SCORE && (! $best || $score > $best['score'])) {
                        $best = [
                            'id' => $definition['id'],
                            'match' => 'fuzzy',
                            'score' => $score,
                        ];
                    }
                }
            }
        }

        if ($best) {
            return [
                'id' => $best['id'],
                'match' => $best['match'],
            ];
        }

        return null;
    }

    private function similarityScore(string $a, string $b): float
    {
        $a = trim($a);
        $b = trim($b);
        if ($a == '' || $b == '') {
            return 0.0;
        }

        similar_text($a, $b, $percent);

        return max(0.0, min(1.0, $percent / 100.0));
    }

    private function definitions(): array
    {
        return [
            [
                'id' => 'admin_versions',
                'title' => 'What is the current Laravel and PHP version?',
                'icon' => 'database',
                'roles' => [UserRole::Admin->value],
                'answer' => 'The HRIS application runs on a modern Laravel and PHP stack. To see the exact Laravel and PHP versions your instance is using, open the Admin dashboard and check the System / AI Health section, which reads these directly from the server.',
            ],
            [
                'id' => 'admin_recent_activity',
                'title' => 'Show me the 5 most recent system activity logs',
                'icon' => 'clock',
                'roles' => [UserRole::Admin->value],
                'aliases' => [
                    'show me recent activity logs',
                    'show recent system activity',
                    'latest activity logs',
                    'recent audit logs',
                    'show me the latest audit log entries',
                    'show the 5 most recent activity logs',
                ],
                'answer' => 'The system activity log records recent admin and HR actions such as logins, configuration changes and AI queries. Use the Admin → Activity Logs screen (or the AI Admin Tools “Recent activity” panel) to view the latest 5 entries with timestamps, actors and actions for quick auditing.',
            ],
            [
                'id' => 'admin_total_users',
                'title' => 'How many total users are there in the system?',
                'icon' => 'users',
                'roles' => [UserRole::Admin->value],
                'aliases' => [
                    'how many users are there',
                    'total users count',
                    'how many user accounts are there',
                    'user accounts count',
                ],
                'answer' => 'Total users are counted from all active and inactive user accounts in the HRIS database (admins, HR staff and employees). You can see the current totals and a breakdown by role in the Admin dashboard statistics or by opening the Users module and checking the summary counters.',
            ],
            [
                'id' => 'admin_active_announcements_holidays',
                'title' => 'Are there any active announcements or upcoming holidays?',
                'icon' => 'calendar',
                'roles' => [UserRole::Admin->value],
                'aliases' => [
                    'any active announcements',
                    'any active posts',
                    'announcements and holidays',
                ],
                'answer' => 'Active announcements are published posts whose effectivity dates are still valid, and upcoming holidays are taken from both the Google calendar feed and custom holiday records. Open the Notices and Calendar modules—or the AI Admin Tools cards—to see which announcements are currently active and which holidays fall in the next few weeks.',
            ],
            [
                'id' => 'admin_pending_approvals',
                'title' => 'How many employees have pending account approvals?',
                'icon' => 'briefcase',
                'roles' => [UserRole::Admin->value],
                'aliases' => [
                    'pending account approvals',
                    'how many pending approvals',
                    'pending approvals count',
                ],
                'answer' => 'Pending account approvals are user registrations that have been created but not yet approved or activated by an administrator. You can see the exact count and review the list in the Admin → Users section by filtering for accounts with a “pending approval” status.',
            ],
            [
                'id' => 'admin_system_health',
                'title' => 'Give me an overview of the system health',
                'icon' => 'lightbulb',
                'roles' => [UserRole::Admin->value],
                'answer' => 'System health summarizes whether the AI data tables exist, required features are enabled, and the local AI server (Ollama) is reachable with acceptable latency. Use the AI Admin Tools “Service Health” panel to quickly see table status, feature flags, and whether the Ollama endpoint is online and responding within the configured time limits.',
            ],
            [
                'id' => 'hr_active_announcements',
                'title' => 'What are the current active announcements/posts?',
                'icon' => 'file',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'priority' => 80,
                'aliases' => [
                    'active announcements',
                    'active posts',
                    'current announcements',
                    'current posts',
                    'latest announcements',
                    'notices',
                    'hr announcements',
                ],
                'answer' => 'Current active announcements are posts that are published and not yet expired based on their validity dates and role scope. HR can review them from the Notices or Posts module, and employees will see only those tagged as visible to “all” or to their specific role.',
            ],
            [
                'id' => 'hr_upcoming_holidays',
                'title' => 'What are the upcoming holidays in the calendar?',
                'icon' => 'calendar',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'priority' => 70,
                'aliases' => [
                    'upcoming holidays',
                    'next holidays',
                    'holiday calendar',
                    'holiday list',
                    'holidays this month',
                ],
                'answer' => 'Upcoming holidays combine entries from the official Google holiday calendar and any custom holidays configured in the HRIS. The Calendar module shows the next few months of holidays, including whether each is regular, special or custom, so HR can plan schedules and leave advisories.',
            ],
            [
                'id' => 'hr_pending_leave',
                'title' => 'How many pending leave applications do we have?',
                'icon' => 'briefcase',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'priority' => 90,
                'aliases' => [
                    'pending leave applications',
                    'pending leave count',
                    'leave applications pending',
                    'pending leave requests',
                    'leave requests pending',
                    'leave approvals pending',
                ],
                'answer' => 'Pending leave applications are requests that have been filed by employees but have not yet been fully approved or rejected. HR can see the exact count and detailed list in the Leave module by filtering applications with “pending” status.',
            ],
            [
                'id' => 'hr_recent_training',
                'title' => 'List the recent employee training records',
                'icon' => 'users',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'priority' => 60,
                'aliases' => [
                    'recent training records',
                    'recent trainings',
                    'latest training records',
                    'latest trainings',
                    'recent training list',
                ],
                'answer' => 'Recent employee training records come from the Training module and include trainings that were recently approved or completed. HR can view these in the Training list, filtered by date range, to see which employees have attended which sessions and their completion status.',
            ],
            [
                'id' => 'hr_employee_count',
                'title' => 'How many employees are there in the system?',
                'icon' => 'users',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'priority' => 50,
                'aliases' => [
                    'how many employees are there',
                    'how about employees',
                    'what about employees',
                    'employee count',
                    'total employees',
                    'employee headcount',
                    'number of employees',
                    'employee population',
                ],
                'answer' => 'The employee count refers to all employee records linked to user accounts in the HRIS, excluding pure admin-only accounts. HR can see the latest headcount and breakdowns on the HR dashboard and in the Employees list, which reflects the current database state.',
            ],
            [
                'id' => 'hr_spms_rating',
                'title' => 'Explain the SPMS rating scale to me',
                'icon' => 'lightbulb',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'aliases' => [
                    'spms rating scale',
                    'spms rating',
                    'spms scale',
                ],
                'answer' => 'The Strategic Performance Management System (SPMS) rating scale describes how individual performance is scored for each performance indicator, from outstanding to unsatisfactory. The official SPMS guidelines in the prompts outline each rating level, its numerical equivalent, and the behavioral or output expectations attached to that score.',
            ],
            [
                'id' => 'hr_paternity_leave',
                'title' => 'What are the requirements for Paternity Leave under RA 8187?',
                'icon' => 'file',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'aliases' => [
                    'paternity leave requirements',
                    'ra 8187 paternity leave',
                ],
                'answer' => 'Under RA 8187, Paternity Leave is granted to married male employees for the first four deliveries of their legitimate spouse with whom they are cohabiting. The policy requires proof of marriage, the child’s birth, and proper filing of the leave form within the prescribed period, as detailed in the Paternity Leave policy document.',
            ],
            [
                'id' => 'hr_year_end_bonus',
                'title' => 'How is the Year-End Bonus and Cash Gift computed?',
                'icon' => 'database',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'answer' => 'The Year-End Bonus and Cash Gift are computed based on the employee’s basic salary and length of service within the applicable fiscal year, following the DBM and CSC rules. The Year-End Bonus policy file explains the percentage of monthly salary used, the cut-off date, and eligibility conditions such as minimum service and employment status.',
            ],
            [
                'id' => 'hr_pbb_eligibility',
                'title' => 'Detail the eligibility criteria for the Performance-Based Bonus (PBB)',
                'icon' => 'briefcase',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'aliases' => [
                    'pbb eligibility',
                    'performance based bonus eligibility',
                ],
                'answer' => 'Performance-Based Bonus (PBB) eligibility depends on both agency-level compliance and individual performance ratings. The PBB policy describes the required performance rating cutoff, absence of certain administrative cases, and agency compliance with SPMS and other national guidelines before an employee can receive the incentive.',
            ],
            [
                'id' => 'hr_special_leave_women',
                'title' => 'What conditions are covered under the Special Leave for Women?',
                'icon' => 'lightbulb',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'answer' => 'Special Leave for Women, under the Magna Carta of Women, covers specified gynecological surgeries that require recuperation. The policy explains which procedures are covered, the maximum number of days that may be granted per year, and the documentary and service requirements before the leave can be approved.',
            ],
            [
                'id' => 'hr_solo_parent_leave',
                'title' => 'What forms and ID are needed for Solo Parent Leave?',
                'icon' => 'file',
                'roles' => [UserRole::Hr->value, UserRole::Admin->value],
                'aliases' => [
                    'solo parent leave requirements',
                    'solo parent leave documents',
                ],
                'answer' => 'Solo Parent Leave requires a valid Solo Parent ID issued by the LGU and supporting documents that prove the employee’s solo parent status, along with the standard leave form. The Solo Parent Leave policy lists the acceptable proofs, renewal periods, and how many working days of leave may be availed each year.',
            ],
            [
                'id' => 'emp_active_announcements',
                'title' => 'Are there any active company announcements right now?',
                'icon' => 'file',
                'roles' => [UserRole::Employee->value],
                'priority' => 70,
                'aliases' => [
                    'company announcements',
                    'active announcements',
                    'active posts',
                    'company notices',
                    'employee announcements',
                    'latest announcements',
                ],
                'answer' => 'Active company announcements are posts that your HR or Admin team has published and that are still within their effectivity dates. You can see them on your dashboard or Notices page, where only announcements tagged for your role (or for all employees) will appear.',
            ],
            [
                'id' => 'emp_next_holiday',
                'title' => 'When is the next upcoming holiday?',
                'icon' => 'calendar',
                'roles' => [UserRole::Employee->value],
                'priority' => 60,
                'aliases' => [
                    'next holiday',
                    'upcoming holiday',
                    'holiday schedule',
                    'holiday list',
                ],
                'answer' => 'The next upcoming holiday is taken from the official holiday calendar plus any custom holidays configured by HR. You can open the Calendar in the HRIS to see the very next holiday date, its type, and whether it is based on a government declaration or an internal office schedule.',
            ],
            [
                'id' => 'emp_leave_balances',
                'title' => 'What are my current leave balances?',
                'icon' => 'briefcase',
                'roles' => [UserRole::Employee->value],
                'priority' => 90,
                'aliases' => [
                    'leave balances',
                    'leave balance',
                    'leave credits',
                    'remaining leave',
                    'available leave',
                    'vacation leave balance',
                    'sick leave balance',
                ],
                'answer' => 'Your current leave balances are computed from your earned credits minus approved and pending applications for each leave type (for example vacation, sick, and special leaves). You can see your latest balances on the Leave module or dashboard, where they are updated automatically whenever HR posts new credits or approves a leave.',
            ],
            [
                'id' => 'emp_pending_leaves',
                'title' => 'How many pending leave applications do I have?',
                'icon' => 'clock',
                'roles' => [UserRole::Employee->value],
                'priority' => 80,
                'aliases' => [
                    'my pending leave applications',
                    'pending leave count',
                    'pending leave requests',
                    'my pending leaves',
                ],
                'answer' => 'Pending leave applications are requests you have filed that are still awaiting review or final decision by your approvers. You can see how many are pending—and their current approver and dates—by opening the Leave module and filtering your applications by “pending” status.',
            ],
            [
                'id' => 'emp_gsis_policy',
                'title' => 'What is the GSIS life insurance policy coverage?',
                'icon' => 'file',
                'roles' => [UserRole::Employee->value],
                'answer' => 'GSIS life insurance coverage depends on your membership status, salary grade, and the specific GSIS program you are enrolled in. The GSIS policy document in the system outlines the basic coverage, premium sharing, and the benefits payable in case of separation, disability or death.',
            ],
            [
                'id' => 'emp_spms',
                'title' => 'Explain how the Performance Appraisal System (SPMS) works',
                'icon' => 'lightbulb',
                'roles' => [UserRole::Employee->value],
                'answer' => 'The SPMS links your individual performance targets to unit and organizational goals through performance commitments and regular appraisals. The SPMS guidelines describe how targets are set, how accomplishments are rated using the SPMS scale, and how ratings translate into rewards or development plans.',
            ],
            [
                'id' => 'emp_mid_year_bonus',
                'title' => 'Am I eligible for the upcoming Mid-Year Bonus?',
                'icon' => 'database',
                'roles' => [UserRole::Employee->value],
                'answer' => 'Eligibility for the Mid-Year Bonus is based on your length of service within the year, employment status, and absence of disqualifying administrative cases, following DBM and CSC rules. The Mid-Year Bonus policy file explains the minimum service requirement, the cut-off date, and how much of your basic pay you may receive if you qualify.',
            ],
            [
                'id' => 'emp_code_of_conduct',
                'title' => 'What are the eight norms of conduct under RA 6713?',
                'icon' => 'file',
                'roles' => [UserRole::Employee->value],
                'aliases' => [
                    'eight norms of conduct',
                    'ra 6713 norms of conduct',
                    'ra 6713 norms',
                    'code of conduct norms',
                ],
                'answer' => 'RA 6713, also known as the Code of Conduct and Ethical Standards for Public Officials and Employees, lists eight norms of conduct such as commitment to public interest, professionalism, justness and sincerity, political neutrality, and responsiveness to the public. The Code of Conduct policy text in the system enumerates and explains each norm with examples.',
            ],
            [
                'id' => 'emp_ssl_vi',
                'title' => 'How does the Salary Standardization Law (SSL) VI affect my salary grade?',
                'icon' => 'briefcase',
                'roles' => [UserRole::Employee->value],
                'answer' => 'SSL VI provides updated salary schedules for government employees, adjusting the salary grade steps over several tranches. The SSL VI policy document explains how your current salary grade and step are mapped to the new rates and the timetable for the implementation of each tranche.',
            ],
            [
                'id' => 'emp_dtr',
                'title' => 'What is the proper way to fill out my Daily Time Record (DTR)?',
                'icon' => 'clock',
                'roles' => [UserRole::Employee->value],
                'answer' => 'The DTR policy describes the correct way to record your time in and time out, including breaks, corrections and authorized adjustments. Employees should ensure entries are complete, legible and aligned with the biometric or attendance system, following the step-by-step guidance in the DTR policy document.',
            ],
        ];
    }

    /**
     * Get answer for a specific suggestion ID.
     */
    public function answerForUser(User $user, string $id, ?string $question = null): string
    {
        $definitions = $this->definitions();
        foreach ($definitions as $definition) {
            if ($definition['id'] === $id) {
                $role = (string) $user->role;
                if (! in_array($role, $definition['roles'], true)) {
                    throw new \InvalidArgumentException('Suggestion not available for user role');
                }

                return $this->generateAnswer($user, $id, $definition, $question);
            }
        }
        throw new \InvalidArgumentException("Unknown suggestion ID: {$id}");
    }

    /**
     * Generate answer text for a suggestion.
     */
    private function generateAnswer(User $user, string $id, array $definition, ?string $question = null): string
    {
        $dynamic = $this->dynamicAnswer($id, $user);
        if ($dynamic !== null) {
            return $dynamic;
        }

        $answer = trim((string) ($definition['answer'] ?? ''));
        if ($answer !== '') {
            return $answer;
        }

        return (string) $definition['title'];
    }

    private function dynamicAnswer(string $id, User $user): ?string
    {
        return match ($id) {
            'admin_versions' => $this->answerVersions(),
            'admin_total_users' => $this->answerTotalUsers(),
            'hr_employee_count' => $this->answerEmployeeCount(),
            'admin_pending_approvals' => $this->answerPendingApprovals(),
            'admin_recent_activity' => $this->answerRecentActivity(),
            'hr_pending_leave' => $this->answerHrPendingLeave(),
            'hr_recent_training' => $this->answerHrRecentTraining(),
            'hr_active_announcements' => $this->answerActiveAnnouncementsForRole(UserRole::Hr->value),
            'hr_upcoming_holidays' => $this->answerUpcomingHolidays(),
            'emp_active_announcements' => $this->answerActiveAnnouncementsForRole(UserRole::Employee->value),
            'emp_next_holiday' => $this->answerUpcomingHolidays(),
            'emp_leave_balances' => $this->answerEmployeeLeaveBalances($user),
            'emp_pending_leaves' => $this->answerEmployeePendingLeaves($user),
            default => null,
        };
    }

    private function answerVersions(): string
    {
        $php = PHP_VERSION;
        $laravel = app()->version();

        return "PHP version: {$php}\nLaravel version: {$laravel}";
    }

    private function answerTotalUsers(): string
    {
        if (! Schema::hasTable('users')) {
            return 'I cannot compute the total users right now because the users table is not available.';
        }

        $total = (int) DB::table('users')->count();

        return "There are {$total} total user account(s) in the system.";
    }

    private function answerEmployeeCount(): string
    {
        if (! Schema::hasTable('employees') || ! Schema::hasTable('users')) {
            return 'I cannot compute the employee count right now because the employees or users table is not available.';
        }

        $count = (int) DB::table('employees')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('users.role', UserRole::Employee->value)
            ->count();

        return "There are {$count} employee(s) in the system (excluding HR and admin accounts).";
    }

    private function answerPendingApprovals(): string
    {
        if (! Schema::hasTable('users')) {
            return 'I cannot compute pending approvals right now because the users table is not available.';
        }

        if (! Schema::hasColumn('users', 'status')) {
            return 'Pending approvals cannot be computed because the users.status column is not available in this environment.';
        }

        $pending = (int) DB::table('users')->where('status', 'pending')->count();

        return "There are {$pending} pending account approval(s).";
    }

    private function answerRecentActivity(): string
    {
        if (! Schema::hasTable('activity_logs')) {
            return 'I cannot load recent system activity right now because the activity logs table is not available.';
        }

        $logs = ActivityLog::with('actor')
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function (ActivityLog $log): string {
                $actor = $log->actor ? trim($log->actor->first_name.' '.$log->actor->last_name) : 'System';
                $date = $log->created_at?->format('Y-m-d H:i') ?? '';

                return "- {$date} — {$actor}: {$log->action}";
            })
            ->values()
            ->all();

        if ($logs === []) {
            return 'No recent activity logs were found.';
        }

        return "Here are the 5 most recent system activity logs:\n".implode("\n", $logs);
    }

    private function answerHrPendingLeave(): string
    {
        if (! Schema::hasTable('leave_applications')) {
            return 'I cannot compute pending leave applications right now because the leave_applications table is not available.';
        }

        $pendingCount = (int) DB::table('leave_applications')
            ->where('status', 'pending')
            ->count();
        $submittedToday = (int) DB::table('leave_applications')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return "Current pending leave applications: {$pendingCount}. Submitted today: {$submittedToday}. You can review them in HR → Leave Applications with the pending filter.";
    }

    private function answerHrRecentTraining(): string
    {
        if (! Schema::hasTable('trainings')) {
            return 'I cannot fetch recent training records right now because the trainings table is not available.';
        }

        $latest = Training::query()
            ->latest('created_at')
            ->limit(5)
            ->get(['employee_name', 'title', 'status', 'date_from'])
            ->map(function (Training $row): string {
                $name = $row->employee_name ?: 'Unknown';
                $title = $row->title ?: 'Untitled';
                $status = $row->status ?: 'unknown';
                $date = $row->date_from?->format('Y-m-d') ?? 'n/a';

                return "- {$name}: {$title} ({$status}, {$date})";
            })
            ->all();

        if ($latest === []) {
            return 'No recent training records were found.';
        }

        return "Here are the 5 most recent training records:\n".implode("\n", $latest);
    }

    private function answerActiveAnnouncementsForRole(string $role): string
    {
        if (! Schema::hasTable('posts')) {
            return 'I cannot fetch active announcements right now because the posts table is not available.';
        }

        $posts = Post::query()
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->whereIn('role_scope', ['all', $role])
            ->latest('created_at')
            ->limit(5)
            ->get(['title', 'expires_at'])
            ->map(function (Post $post): string {
                $expires = $post->expires_at?->format('Y-m-d') ?? 'no expiry';

                return "- {$post->title} (expires: {$expires})";
            })
            ->all();

        if ($posts === []) {
            return 'There are no active announcements right now for your role scope.';
        }

        return "Active announcements for your role:\n".implode("\n", $posts);
    }

    private function answerUpcomingHolidays(): string
    {
        if (! Schema::hasTable('custom_holidays')) {
            return 'I cannot fetch upcoming holidays right now because the custom_holidays table is not available.';
        }

        $holidays = CustomHoliday::query()
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->limit(5)
            ->get(['title', 'date', 'category'])
            ->map(function (CustomHoliday $holiday): string {
                $date = $holiday->date?->format('Y-m-d') ?? 'n/a';
                $category = $holiday->category ?: 'custom';

                return "- {$date}: {$holiday->title} ({$category})";
            })
            ->all();

        if ($holidays === []) {
            return 'No upcoming custom holidays were found in the HRIS calendar.';
        }

        return "Upcoming holidays from the HRIS calendar:\n".implode("\n", $holidays);
    }

    private function answerEmployeeLeaveBalances(User $user): string
    {
        $employeeId = (int) ($user->employee?->id ?? 0);
        if ($employeeId <= 0) {
            return 'I cannot load your leave balances right now because your employee profile is not linked.';
        }
        if (! Schema::hasTable('leave_credits')) {
            return 'I cannot load your leave balances right now because the leave_credits table is not available.';
        }

        $credits = LeaveCredit::query()
            ->where('employee_id', $employeeId)
            ->orderBy('leave_type')
            ->get(['leave_type', 'balance'])
            ->map(fn (LeaveCredit $credit): string => "- {$credit->leave_type}: ".number_format((float) $credit->balance, 2).' day(s)')
            ->all();

        if ($credits === []) {
            return 'You currently have no leave credits posted yet. Please contact HR to verify your leave credit setup.';
        }

        return "Your current leave balances are:\n".implode("\n", $credits);
    }

    private function answerEmployeePendingLeaves(User $user): string
    {
        $employeeId = (int) ($user->employee?->id ?? 0);
        if ($employeeId <= 0) {
            return 'I cannot load your pending leave applications right now because your employee profile is not linked.';
        }
        if (! Schema::hasTable('leave_applications')) {
            return 'I cannot load your pending leave applications right now because the leave_applications table is not available.';
        }

        $pending = LeaveApplication::query()
            ->where(function ($q) use ($employeeId, $user) {
                $q->where('employee_fk', $employeeId)
                    ->orWhere('employee_id', (string) $user->id);
            })
            ->where('status', 'pending')
            ->count();

        return "You currently have {$pending} pending leave application(s).";
    }

    /**
     * Get source information for a suggestion.
     */
    public function sourceForSuggestion(string $id): ?array
    {
        $definitions = $this->definitions();
        foreach ($definitions as $definition) {
            if ($definition['id'] === $id) {
                // Map suggestion IDs that have a real backing policy file.
                $policyMap = [
                    'hr_spms_rating' => 'spms_policies.txt',
                    'hr_paternity_leave' => 'paternity_leave_policies.txt',
                    'hr_year_end_bonus' => 'year_end_bonus_policies.txt',
                    'hr_pbb_eligibility' => 'pbb_policies.txt',
                    'hr_special_leave_women' => 'special_leave_women_policies.txt',
                    'hr_solo_parent_leave' => 'solo_parent_leave_policies.txt',
                    'emp_code_of_conduct' => 'code_of_conduct.txt',
                    'emp_gsis_policy' => 'gsis_policies.txt',
                    'emp_spms' => 'spms_policies.txt',
                    'emp_ssl_vi' => 'ssl_vi_policies.txt',
                    'emp_mid_year_bonus' => 'mid_year_bonus_policies.txt',
                    'emp_dtr' => 'dtr_policies.txt',
                ];

                if (! isset($policyMap[$id])) {
                    // No real policy file mapped: treat this as a system explanation
                    // and do not return a source so the UI shows no document link.
                    return null;
                }

                $sourceFile = $policyMap[$id];
                $path = storage_path('app/prompts/'.$sourceFile);
                if (! file_exists($path)) {
                    // Mapped file does not exist in this environment; also omit source.
                    return null;
                }

                return [
                    'source' => $sourceFile,
                    'id' => $id,
                ];
            }
        }

        return null;
    }
}
