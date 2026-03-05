<?php

namespace App\Features\Leave\Services;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveCredit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveAccrualService
{
    public function accrueMonthly(?Carbon $forMonth = null): array
    {
        $month = ($forMonth ?? now())->copy()->startOfMonth();
        $marker = $this->marker('accrual', $month);

        $employees = Employee::query()->pluck('id')->all();

        $createdAdjustments = 0;

        DB::transaction(function () use ($employees, $marker, &$createdAdjustments) {
            foreach ($employees as $employeeId) {
                foreach (LeaveTypeRules::definitions() as $leaveType => $def) {
                    if (! LeaveTypeRules::accruesMonthly($leaveType)) {
                        continue;
                    }

                    $credit = LeaveCredit::firstOrCreate(
                        ['employee_id' => $employeeId, 'leave_type' => $leaveType],
                        ['balance' => 0]
                    );

                    $already = $credit->adjustments()
                        ->where('reason', $marker.':'.$leaveType)
                        ->exists();

                    if ($already) {
                        continue;
                    }

                    $amount = LeaveTypeRules::monthlyAccrual($leaveType);
                    if ($amount <= 0) {
                        continue;
                    }

                    $credit->adjust(
                        amount: $amount,
                        reason: $marker.':'.$leaveType,
                        userId: null,
                    );

                    $createdAdjustments++;
                }
            }
        });

        return [
            'month' => $month->toDateString(),
            'adjustments_created' => $createdAdjustments,
        ];
    }

    public function resetAnnualNonCumulative(?int $year = null): array
    {
        $y = $year ?? (int) now()->year;
        $marker = $this->marker('reset', Carbon::create($y, 1, 1));

        $employees = Employee::query()->pluck('id')->all();
        $resets = 0;

        DB::transaction(function () use ($employees, $marker, &$resets) {
            foreach ($employees as $employeeId) {
                foreach (LeaveTypeRules::definitions() as $leaveType => $def) {
                    if (LeaveTypeRules::cumulative($leaveType)) {
                        continue;
                    }

                    $credit = LeaveCredit::firstOrCreate(
                        ['employee_id' => $employeeId, 'leave_type' => $leaveType],
                        ['balance' => 0]
                    );

                    $already = $credit->adjustments()
                        ->where('reason', $marker.':'.$leaveType)
                        ->exists();

                    if ($already) {
                        continue;
                    }

                    $current = (float) $credit->balance;
                    if ($current !== 0.0) {
                        $credit->adjust(
                            amount: -$current,
                            reason: $marker.':'.$leaveType,
                            userId: null,
                        );
                    } else {
                        $credit->adjustments()->create([
                            'amount' => 0,
                            'reason' => $marker.':'.$leaveType,
                            'created_by' => null,
                        ]);
                    }

                    $resets++;
                }
            }
        });

        return [
            'year' => $y,
            'resets_processed' => $resets,
        ];
    }

    private function marker(string $type, Carbon $date): string
    {
        if ($type === 'accrual') {
            return 'SYSTEM:ACCRUAL:'.$date->format('Y-m');
        }

        return 'SYSTEM:RESET:'.$date->format('Y');
    }
}
