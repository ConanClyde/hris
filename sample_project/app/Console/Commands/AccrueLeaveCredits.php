<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AccrueLeaveCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:accrue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accrue monthly leave credits (1.25 days) for active employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting leave accrual...');

        $employees = \App\Features\Employees\Models\Employee::where('status', 'active')->get();

        $count = 0;
        foreach ($employees as $employee) {
            // Vacation Leave
            $vl = $employee->leaveCredits()->firstOrCreate(
                ['leave_type' => 'Vacation Leave'],
                ['balance' => 0]
            );
            $vl->adjust(1.25, 'Monthly Accrual');

            // Sick Leave
            $sl = $employee->leaveCredits()->firstOrCreate(
                ['leave_type' => 'Sick Leave'],
                ['balance' => 0]
            );
            $sl->adjust(1.25, 'Monthly Accrual');

            $count++;
        }

        $this->info("Accrued credits for {$count} employees.");
    }
}
