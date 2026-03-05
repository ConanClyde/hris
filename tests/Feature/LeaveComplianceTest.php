<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveComplianceTest extends TestCase
{
    use RefreshDatabase;

    public function test_forced_leave_charges_to_vl(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $employeeUser->id]);

        $vl = LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Vacation Leave',
            'balance' => 10,
        ]);

        LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Mandatory/Forced Leave',
            'balance' => 5,
        ]);

        $application = LeaveApplication::create([
            'employee_id' => (string) $employee->id,
            'employee_fk' => $employee->id,
            'employee_name' => $employee->full_name,
            'type' => 'Mandatory/Forced Leave',
            'date_from' => now()->addDays(2),
            'total_days' => 5,
            'reason' => null,
            'status' => 'pending',
        ]);

        $hrUser = User::factory()->create(['role' => 'hr']);

        $res = $this->actingAs($hrUser)->put(route('hr.leave-applications.update', $application->id), [
            'employee_id' => (string) $employee->id,
            'status' => 'approved',
            'type' => $application->type,
            'date_from' => $application->date_from->toDateString(),
            'total_days' => 5,
            'reason' => '',
        ]);

        $res->assertRedirect();
        $error = (string) session('error', '');
        if ($error !== '') {
            fwrite(STDERR, "Approval error: {$error}\n");
        }
        $res->assertSessionMissing('error');

        $this->assertDatabaseHas('leave_applications', [
            'id' => $application->id,
            'status' => 'approved',
            'type' => 'Mandatory/Forced Leave',
        ]);

        $vl->refresh();
        $this->assertSame(5.0, (float) $vl->balance);
    }

    public function test_wellness_annual_cap_is_enforced(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $employeeUser->id]);

        LeaveApplication::create([
            'employee_id' => (string) $employee->id,
            'employee_fk' => $employee->id,
            'employee_name' => $employee->full_name,
            'type' => 'Wellness Leave',
            'date_from' => now()->startOfYear()->addDays(10),
            'total_days' => 5,
            'status' => 'approved',
        ]);

        LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Wellness Leave',
            'balance' => 5,
        ]);

        $application = LeaveApplication::create([
            'employee_id' => (string) $employee->id,
            'employee_fk' => $employee->id,
            'employee_name' => $employee->full_name,
            'type' => 'Wellness Leave',
            'date_from' => now()->startOfYear()->addDays(20),
            'total_days' => 1,
            'status' => 'pending',
        ]);

        $hrUser = User::factory()->create(['role' => 'hr']);

        $res = $this->actingAs($hrUser)->put(route('hr.leave-applications.update', $application->id), [
            'employee_id' => (string) $employee->id,
            'status' => 'approved',
            'type' => $application->type,
            'date_from' => $application->date_from->toDateString(),
            'total_days' => 1,
            'reason' => '',
        ]);

        $res->assertSessionHas('error');

        $this->assertDatabaseHas('leave_applications', [
            'id' => $application->id,
            'status' => 'pending',
        ]);
    }

    public function test_lwop_blocked_when_credits_remain(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $employeeUser->id]);

        LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Vacation Leave',
            'balance' => 1,
        ]);

        $application = LeaveApplication::create([
            'employee_id' => (string) $employee->id,
            'employee_fk' => $employee->id,
            'employee_name' => $employee->full_name,
            'type' => 'Leave Without Pay (LWOP)',
            'date_from' => now()->addDays(1),
            'total_days' => 1,
            'status' => 'pending',
        ]);

        $hrUser = User::factory()->create(['role' => 'hr']);

        $res = $this->actingAs($hrUser)->put(route('hr.leave-applications.update', $application->id), [
            'employee_id' => (string) $employee->id,
            'status' => 'approved',
            'type' => $application->type,
            'date_from' => $application->date_from->toDateString(),
            'total_days' => 1,
            'reason' => '',
        ]);

        $res->assertSessionHas('error');
    }

    public function test_monthly_accrual_is_idempotent(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $employeeUser->id]);

        $month = Carbon::create(2026, 1, 1);

        $this->artisan('leave:accrue-monthly', ['--month' => $month->format('Y-m')])
            ->assertExitCode(0);

        $this->artisan('leave:accrue-monthly', ['--month' => $month->format('Y-m')])
            ->assertExitCode(0);

        $vl = LeaveCredit::query()
            ->where('employee_id', $employee->id)
            ->where('leave_type', 'Vacation Leave')
            ->first();

        $sl = LeaveCredit::query()
            ->where('employee_id', $employee->id)
            ->where('leave_type', 'Sick Leave')
            ->first();

        $this->assertNotNull($vl);
        $this->assertNotNull($sl);
        $this->assertSame(1.25, (float) $vl->balance);
        $this->assertSame(1.25, (float) $sl->balance);
    }
}
