<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use App\Features\Pds\Models\Pds;
use App\Features\Training\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    protected $employeeUser;

    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employeeUser = User::factory()->create([
            'role' => 'employee',
        ]);

        $this->employee = Employee::factory()->create([
            'user_id' => $this->employeeUser->id,
        ]);
    }

    public function test_employee_can_view_leave_applications()
    {
        $response = $this->actingAs($this->employeeUser)->get('/employee/leave-applications');
        $response->assertStatus(200);
    }

    public function test_employee_can_view_pds()
    {
        Pds::create([
            'employee_id' => $this->employee->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/pds');
        $response->assertStatus(200);
    }

    public function test_employee_can_view_training()
    {
        Training::create([
            'employee_id' => $this->employee->id,
            'title' => 'Test Training',
            'date_from' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/training');
        $response->assertStatus(200);
    }

    public function test_employee_can_create_leave_application()
    {
        $response = $this->actingAs($this->employeeUser)->post('/employee/leave-applications', [
            'type' => 'Vacation Leave',
            'date_from' => now()->addDays(5)->toDateString(),
            'total_days' => 2,
            'reason' => 'Vacation',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leave_applications', [
            'employee_id' => $this->employee->id,
            'type' => 'Vacation Leave',
            'total_days' => 2,
            'status' => 'pending',
        ]);
    }

    public function test_leave_credits_deducted_on_approval()
    {
        // 1. Setup Leave Credit
        $credit = LeaveCredit::create([
            'employee_id' => $this->employee->id,
            'leave_type' => 'Vacation Leave',
            'balance' => 10,
        ]);

        // 2. Create Leave Application
        $application = LeaveApplication::create([
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->full_name,
            'type' => 'Vacation Leave',
            'date_from' => now()->addDays(5),
            'total_days' => 2,
            'reason' => 'Vacation',
            'status' => 'pending',
        ]);

        // 3. Login as HR and Approve (employee_id as string to match leave_applications.employee_id column)
        /** @var \App\Models\User $hrUser */
        $hrUser = User::factory()->create(['role' => 'hr']);

        $response = $this->actingAs($hrUser)->put(route('hr.leave-applications.update', $application->id), [
            'employee_id' => (string) $this->employee->id,
            'status' => 'approved',
            'type' => 'Vacation Leave',
            'date_from' => $application->date_from->toDateString(),
            'total_days' => 2,
        ]);

        $response->assertRedirect();

        // 4. Verify Credit Deduction
        $this->assertDatabaseHas('leave_applications', [
            'id' => $application->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('leave_credits', [
            'id' => $credit->id,
            'balance' => 8, // 10 - 2 = 8
        ]);
    }
}
