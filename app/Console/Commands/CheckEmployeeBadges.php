<?php

namespace App\Console\Commands;

use App\Features\Employees\Models\Employee;
use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckEmployeeBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badges:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Evaluate employees and award badges based on criteria.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting badge evaluation...');

        $badges = Badge::where('is_active', true)->get();
        if ($badges->isEmpty()) {
            $this->info('No active badges found. Exiting.');

            return;
        }

        $employees = Employee::with('badges', 'pds')->get();
        $awardedCount = 0;

        foreach ($employees as $employee) {
            $earnedBadgeIds = $employee->badges->pluck('id')->toArray();

            foreach ($badges as $badge) {
                // Skip if already earned
                if (in_array($badge->id, $earnedBadgeIds)) {
                    continue;
                }

                if ($this->employeeMeetsCriteria($employee, $badge->criteria_type)) {
                    $employee->badges()->attach($badge->id, ['awarded_at' => now()]);
                    $awardedCount++;
                    $this->info("Awarded '{$badge->name}' to {$employee->first_name} {$employee->last_name}");
                }
            }
        }

        $this->info("Badge evaluation complete. Awarded {$awardedCount} new badges.");
    }

    private function employeeMeetsCriteria(Employee $employee, string $criteriaType): bool
    {
        return match ($criteriaType) {
            'tenure_1_year' => $this->checkTenure($employee, 1),
            'tenure_3_years' => $this->checkTenure($employee, 3),
            'tenure_5_years' => $this->checkTenure($employee, 5),
            'tenure_10_years' => $this->checkTenure($employee, 10),
            'profile_100_percent' => $this->checkProfileCompleteness($employee),
            default => false,
        };
    }

    private function checkTenure(Employee $employee, int $years): bool
    {
        if (! $employee->date_hired) {
            return false;
        }

        $hireDate = Carbon::parse($employee->date_hired);

        return $hireDate->diffInYears(now()) >= $years;
    }

    private function checkProfileCompleteness(Employee $employee): bool
    {
        // Simple check: do they have an approved PDS?
        $latestPds = $employee->pds()->latest('created_at')->first();

        return $latestPds && $latestPds->status === 'approved';
    }
}
