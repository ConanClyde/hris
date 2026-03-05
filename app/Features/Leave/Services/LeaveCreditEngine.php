<?php

namespace App\Features\Leave\Services;

use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use Illuminate\Support\Facades\DB;

class LeaveCreditEngine
{
    public function validateForApproval(LeaveApplication $leave): array
    {
        $errors = [];

        $type = (string) $leave->type;

        if (! LeaveTypeRules::exists($type)) {
            $errors[] = "Unsupported leave type: {$type}.";

            return $errors;
        }

        // Check for overlapping approved leave
        if ($leave->employee_fk !== null) {
            $overlapErrors = $this->checkForOverlappingLeave($leave);
            $errors = array_merge($errors, $overlapErrors);
        }

        $year = optional($leave->date_from)->year ?? now()->year;

        $cap = LeaveTypeRules::annualCap($type);
        if ($cap !== null && $leave->employee_fk !== null) {
            $used = LeaveApplication::query()
                ->where('employee_fk', $leave->employee_fk)
                ->where('type', $type)
                ->where('status', 'approved')
                ->whereYear('date_from', $year)
                ->when($leave->id !== null, fn ($q) => $q->where('id', '!=', $leave->id))
                ->sum('total_days');

            if (((float) $used + (float) $leave->total_days) > $cap) {
                $errors[] = "Annual cap exceeded for {$type}. Remaining: ".max(0, $cap - (float) $used);
            }
        }

        if ($type === 'Leave Without Pay (LWOP)' && $leave->employee_fk !== null) {
            $vl = $this->balanceFor($leave->employee_fk, 'Vacation Leave');
            $sl = $this->balanceFor($leave->employee_fk, 'Sick Leave');
            if ($vl > 0 || $sl > 0) {
                $errors[] = 'LWOP cannot be approved while leave credits remain.';
            }
        }

        $chargedTo = LeaveTypeRules::chargedTo($type);
        if (
            $leave->employee_fk !== null
            && $chargedTo !== 'Leave Without Pay (LWOP)'
            && LeaveTypeRules::deductsCredits($type)
        ) {
            $balance = $this->balanceFor($leave->employee_fk, $chargedTo);
            if ($balance < (float) $leave->total_days) {
                $errors[] = "Insufficient {$chargedTo} credits. Balance: {$balance}.";
            }
        }

        return $errors;
    }

    /**
     * Check for overlapping approved leave periods for the same employee.
     *
     * @return array<string>
     */
    private function checkForOverlappingLeave(LeaveApplication $leave): array
    {
        $errors = [];

        if ($leave->date_from === null || $leave->date_to === null) {
            return $errors;
        }

        $overlapping = LeaveApplication::query()
            ->where('employee_fk', $leave->employee_fk)
            ->where('status', 'approved')
            ->where(function ($query) use ($leave) {
                // New leave starts during existing leave
                $query->where(function ($q) use ($leave) {
                    $q->where('date_from', '<=', $leave->date_from)
                        ->where('date_to', '>=', $leave->date_from);
                })
                // New leave ends during existing leave
                    ->orWhere(function ($q) use ($leave) {
                        $q->where('date_from', '<=', $leave->date_to)
                            ->where('date_to', '>=', $leave->date_to);
                    })
                // New leave completely covers existing leave
                    ->orWhere(function ($q) use ($leave) {
                        $q->where('date_from', '>=', $leave->date_from)
                            ->where('date_to', '<=', $leave->date_to);
                    });
            })
            ->when($leave->id !== null, fn ($q) => $q->where('id', '!=', $leave->id))
            ->first();

        if ($overlapping !== null) {
            $errors[] = "Employee already has approved leave from {$overlapping->date_from->format('M d, Y')} to {$overlapping->date_to->format('M d, Y')}. Cannot approve overlapping leave periods.";
        }

        return $errors;
    }

    public function applyApprovalDeduction(LeaveApplication $leave, ?int $actorUserId = null): void
    {
        $type = (string) $leave->type;
        if (! LeaveTypeRules::exists($type)) {
            return;
        }

        $chargedTo = LeaveTypeRules::chargedTo($type);

        if ($leave->employee_fk === null) {
            return;
        }

        if ($chargedTo === 'Leave Without Pay (LWOP)' || ! LeaveTypeRules::deductsCredits($type)) {
            return;
        }

        DB::transaction(function () use ($leave, $chargedTo, $actorUserId) {
            $credit = LeaveCredit::firstOrCreate(
                ['employee_id' => $leave->employee_fk, 'leave_type' => $chargedTo],
                ['balance' => 0]
            );

            $credit->adjust(
                amount: -((float) $leave->total_days),
                reason: "Leave application #{$leave->id} approved ({$leave->type})",
                userId: $actorUserId,
            );
        });
    }

    private function balanceFor(int $employeeId, string $leaveType): float
    {
        $credit = LeaveCredit::query()
            ->where('employee_id', $employeeId)
            ->where('leave_type', $leaveType)
            ->first();

        return $credit ? (float) $credit->balance : 0.0;
    }
}
