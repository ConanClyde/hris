<?php

namespace App\Features\Leave\Console\Commands;

use App\Features\Leave\Services\LeaveAccrualService;
use Illuminate\Console\Command;

class LeaveResetAnnualNonCumulative extends Command
{
    protected $signature = 'leave:reset-annual {--year= : Target year (YYYY), defaults to current year}';

    protected $description = 'Reset non-cumulative leave buckets for the year (e.g., Wellness, SLP).';

    public function handle(LeaveAccrualService $service): int
    {
        $yearOpt = $this->option('year');
        $year = null;

        if (is_string($yearOpt) && $yearOpt !== '') {
            $year = (int) $yearOpt;
        }

        $result = $service->resetAnnualNonCumulative($year);

        $this->info('Reset year: '.$result['year']);
        $this->info('Leave types reset processed: '.$result['resets_processed']);

        return self::SUCCESS;
    }
}
