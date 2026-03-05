<?php

namespace App\Features\Leave\Console\Commands;

use App\Features\Leave\Services\LeaveAccrualService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LeaveAccrueMonthly extends Command
{
    protected $signature = 'leave:accrue-monthly {--month= : Target month (YYYY-MM), defaults to current month}';

    protected $description = 'Accrue monthly leave credits (e.g., VL/SL 1.25 per month) with idempotency markers.';

    public function handle(LeaveAccrualService $service): int
    {
        $monthOpt = $this->option('month');
        $month = null;

        if (is_string($monthOpt) && $monthOpt !== '') {
            $month = Carbon::createFromFormat('Y-m', $monthOpt)->startOfMonth();
        }

        $result = $service->accrueMonthly($month);

        $this->info('Accrual month: '.$result['month']);
        $this->info('Adjustments created: '.$result['adjustments_created']);

        return self::SUCCESS;
    }
}
