<?php

namespace App\Features\Leave\Services;

use App\Features\Leave\Enums\LeaveType;

class LeaveTypeRules
{
    /**
     * @return array<string, array{
     *   accrues_monthly?: bool,
     *   monthly_accrual?: float,
     *   annual_entitlement?: float,
     *   cumulative?: bool,
     *   annual_cap?: float,
     *   charged_to?: string,
     *   credit_deducts_from_charged_to?: bool,
     *   requires_approval?: bool,
     * }>
     */
    public static function definitions(): array
    {
        return [
            LeaveType::Vacation->value => [
                'accrues_monthly' => true,
                'monthly_accrual' => 1.25,
                'annual_entitlement' => 15.0,
                'cumulative' => true,
                'requires_approval' => true,
            ],
            LeaveType::Sick->value => [
                'accrues_monthly' => true,
                'monthly_accrual' => 1.25,
                'annual_entitlement' => 15.0,
                'cumulative' => true,
                'requires_approval' => true,
            ],
            LeaveType::MandatoryForced->value => [
                'annual_cap' => 5.0,
                'charged_to' => LeaveType::Vacation->value,
                'credit_deducts_from_charged_to' => true,
                'requires_approval' => true,
            ],
            LeaveType::Wellness->value => [
                'annual_cap' => 5.0,
                'cumulative' => false,
                'requires_approval' => true,
            ],
            LeaveType::SpecialLeavePrivileges->value => [
                'annual_cap' => 3.0,
                'cumulative' => false,
                'requires_approval' => true,
            ],
            LeaveType::Paternity->value => [
                'annual_cap' => 7.0,
                'cumulative' => false,
                'requires_approval' => true,
            ],
            LeaveType::SoloParent->value => [
                'annual_cap' => 7.0,
                'cumulative' => false,
                'requires_approval' => true,
            ],
            LeaveType::Study->value => [
                'annual_cap' => 180.0,
                'cumulative' => false,
                'requires_approval' => true,
            ],
            LeaveType::Maternity->value => [
                'annual_cap' => 105.0,
                'cumulative' => false,
                'requires_approval' => true,
            ],
            LeaveType::LeaveWithoutPay->value => [
                'requires_approval' => true,
            ],
        ];
    }

    public static function exists(string $leaveType): bool
    {
        return array_key_exists($leaveType, self::definitions());
    }

    public static function chargedTo(string $leaveType): string
    {
        $def = self::definitions()[$leaveType] ?? [];

        return (string) ($def['charged_to'] ?? $leaveType);
    }

    public static function deductsCredits(string $leaveType): bool
    {
        $def = self::definitions()[$leaveType] ?? [];

        return (bool) ($def['credit_deducts_from_charged_to'] ?? true);
    }

    public static function annualCap(string $leaveType): ?float
    {
        $def = self::definitions()[$leaveType] ?? [];
        $cap = $def['annual_cap'] ?? null;

        return $cap === null ? null : (float) $cap;
    }

    public static function accruesMonthly(string $leaveType): bool
    {
        $def = self::definitions()[$leaveType] ?? [];

        return (bool) ($def['accrues_monthly'] ?? false);
    }

    public static function monthlyAccrual(string $leaveType): float
    {
        $def = self::definitions()[$leaveType] ?? [];

        return (float) ($def['monthly_accrual'] ?? 0.0);
    }

    public static function cumulative(string $leaveType): bool
    {
        $def = self::definitions()[$leaveType] ?? [];

        return (bool) ($def['cumulative'] ?? true);
    }
}
