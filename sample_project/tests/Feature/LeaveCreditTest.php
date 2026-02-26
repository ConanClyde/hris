<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveCredit;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveCreditTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_have_leave_credits()
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        $credit = LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Vacation Leave',
            'balance' => 10.0,
        ]);

        $this->assertDatabaseHas('leave_credits', [
            'employee_id' => $employee->id,
            'leave_type' => 'Vacation Leave',
            'balance' => 10.0,
        ]);

        $this->assertEquals(1, $employee->leaveCredits()->count());
    }

    public function test_adjust_method_updates_balance_and_creates_ledger()
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create(['user_id' => $user->id]);
        $credit = LeaveCredit::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Sick Leave',
            'balance' => 5.0,
        ]);

        // Add 5 days
        $credit->adjust(5.0, 'Monthly Accrual', $user->id);

        $this->assertEquals(10.0, $credit->fresh()->balance);

        $this->assertDatabaseHas('leave_adjustments', [
            'leave_credit_id' => $credit->id,
            'amount' => 5.0,
            'reason' => 'Monthly Accrual',
            'created_by' => $user->id,
        ]);

        // Deduct 2 days
        $credit->adjust(-2.5, 'Leave Application', $user->id);

        $this->assertEquals(7.5, $credit->fresh()->balance);

        $this->assertDatabaseHas('leave_adjustments', [
            'leave_credit_id' => $credit->id,
            'amount' => -2.5,
            'reason' => 'Leave Application',
        ]);
    }
}
