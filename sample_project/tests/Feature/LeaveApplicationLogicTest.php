<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveApplicationLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_cannot_apply_with_insufficient_credits()
    {
        $user = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        // Create credit with 5 days balance
        LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Vacation Leave',
            'balance' => 5.0,
        ]);

        $this->actingAs($user);

        // Apply for 10 days
        $response = $this->post(route('employee.leave-applications.store'), [
            'leave_type' => 'Vacation Leave',
            'date_from' => now()->toDateString(),
            'total_days' => 10,
            'reason' => 'Long vacation',
        ]);

        $response->assertSessionHasErrors(['leave_type']);
        $this->assertDatabaseMissing('leave_applications', ['reason' => 'Long vacation']);
    }

    public function test_employee_can_apply_with_sufficient_credits_and_balance_remains_until_approval()
    {
        $user = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        $credit = LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Vacation Leave',
            'balance' => 10.0,
        ]);

        $this->actingAs($user);

        // Apply for 5 days
        $response = $this->post(route('employee.leave-applications.store'), [
            'leave_type' => 'Vacation Leave',
            'date_from' => now()->toDateString(),
            'total_days' => 5,
            'reason' => 'Short vacation',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check application created
        $this->assertDatabaseHas('leave_applications', [
            'employee_id' => $employee->id,
            'total_days' => 5,
            'status' => 'pending',
        ]);

        // Check balance UNCHANGED (deduction happens on approval)
        $this->assertEquals(10.0, $credit->fresh()->balance);
    }

    public function test_hr_approval_deducts_credits()
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $empUser = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $empUser->id]);

        $credit = LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Sick Leave',
            'balance' => 10.0,
        ]);

        $leave = LeaveApplication::factory()->create([
            'employee_id' => $employee->id,
            'type' => 'Sick Leave',
            'total_days' => 3.0,
            'status' => 'pending',
        ]);

        $this->actingAs($hrUser);

        // Approve
        $response = $this->put(route('hr.leave-applications.update', $leave->id), [
            'employee_id' => $employee->id, // Required by validation
            'leave_type' => 'Sick Leave',
            'date_from' => $leave->date_from->toDateString(),
            'total_days' => 3.0,
            'reason' => $leave->reason,
            'status' => 'approved',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check status updated
        $this->assertEquals('approved', $leave->fresh()->status->value ?? $leave->fresh()->status);

        // Check balance deducted
        $this->assertEquals(7.0, $credit->fresh()->balance);

        // Check ledger entry
        $this->assertDatabaseHas('leave_adjustments', [
            'leave_credit_id' => $credit->id,
            'amount' => -3.0,
            'created_by' => $hrUser->id,
        ]);
    }

    public function test_hr_approval_fails_if_insufficient_credits()
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $empUser = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $empUser->id]);

        $credit = LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Emergency Leave',
            'balance' => 2.0,
        ]);

        $leave = LeaveApplication::factory()->create([
            'employee_id' => $employee->id,
            'type' => 'Emergency Leave',
            'total_days' => 5.0,
            'status' => 'pending',
        ]);

        $this->actingAs($hrUser);

        // Try to approve
        $response = $this->put(route('hr.leave-applications.update', $leave->id), [
            'employee_id' => $employee->id,
            'leave_type' => 'Emergency Leave',
            'date_from' => $leave->date_from->toDateString(),
            'total_days' => 5.0,
            'reason' => $leave->reason,
            'status' => 'approved',
        ]);

        $response->assertSessionHasErrors(['status']); // Expect error on 'status' field

        // Check balance unchanged
        $this->assertEquals(2.0, $credit->fresh()->balance);

        // Check status unchanged (transaction rolled back)
        $this->assertEquals('pending', $leave->fresh()->status->value ?? $leave->fresh()->status);
    }
}
