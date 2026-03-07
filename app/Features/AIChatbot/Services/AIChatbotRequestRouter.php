<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Services;

use App\Models\User;

class AIChatbotRequestRouter
{
    public const MODE_PUBLIC = 'public';
    public const MODE_HRIS = 'hris';
    public const MODE_REFUSE = 'refuse';

    /**
     * @return array{mode: self::MODE_*, reason?: string}
     */
    public function route(User $user, string $message): array
    {
        $q = mb_strtolower(trim($message));

        if ($q === '') {
            return ['mode' => self::MODE_PUBLIC];
        }

        // Obvious personal/PII extraction attempts (very lightweight heuristic).
        // Final enforcement must still be server-side in tools.
        $piiSignals = [
            'ssn', 'tin', 'salary', 'payroll', 'address', 'phone number', 'email address',
            'bank', 'account number', 'birthday', 'date of birth', 'civil status',
        ];

        foreach ($piiSignals as $sig) {
            if (str_contains($q, $sig)) {
                return ['mode' => self::MODE_REFUSE, 'reason' => 'pii_signal'];
            }
        }

        // Security/privilege escalation signals (treat as HRIS so analysis + permission checks can run).
        $securitySignals = [
            'administrator',
            'administrators',
            'system administrator',
            'system administrators',
            'admin-only',
            'access level',
            'access levels',
            'permissions',
            'roles',
            'list every',
            'list all users',
            'list users',
            'who are the admins',
            'who is admin',
        ];

        foreach ($securitySignals as $sig) {
            if (str_contains($q, $sig)) {
                return ['mode' => self::MODE_HRIS, 'reason' => 'security_signal'];
            }
        }

        // Statistics/count questions should run through HRIS mode so the assistant can use tools.
        $statsSignals = [
            'how many',
            'count',
            'total',
            'number of',
            'statistics',
            'stats',
        ];

        foreach ($statsSignals as $sig) {
            if (str_contains($q, $sig)) {
                return ['mode' => self::MODE_HRIS, 'reason' => 'stats_signal'];
            }
        }

        // HRIS domain signals.
        $hrisSignals = [
            'leave', 'pds', 'training', 'announcement', 'notice', 'policy', 'hris',
            'employee', 'employees', 'division', 'subdivision', 'section', 'activity log',
        ];

        foreach ($hrisSignals as $sig) {
            if (str_contains($q, $sig)) {
                return ['mode' => self::MODE_HRIS];
            }
        }

        return ['mode' => self::MODE_PUBLIC];
    }
}
