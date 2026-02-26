<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveCrudTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ─────────────────────────────────────

    private function employeeWithUser(string $role = 'employee'): array
    {
        $user = User::factory()->create(['role' => $role]);
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        return [$user, $employee];
    }

    // ── Auth Guards ─────────────────────────────────

    public function test_guest_cannot_access_employee_leave_index(): void
    {
        $response = $this->get(route('employee.leave-applications.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_employee_cannot_access_hr_leave(): void
    {
        [$user] = $this->employeeWithUser('employee');
        $this->actingAs($user);

        $response = $this->get(route('hr.leave-applications.index'));
        $response->assertRedirect(route('dashboard'));
    }

    // ── Employee Leave Index ────────────────────────

    public function test_employee_can_view_own_leave_applications(): void
    {
        [$user, $employee] = $this->employeeWithUser();
        $this->actingAs($user);

        LeaveApplication::factory()->create([
            'employee_id' => $employee->id,
            'type' => 'Vacation Leave',
            'status' => 'pending',
        ]);

        $response = $this->get(route('employee.leave-applications.index'));
        $response->assertStatus(200);
        $response->assertSee('Vacation Leave');
    }

    // ── Employee Leave Store ────────────────────────

    public function test_employee_can_submit_leave_application(): void
    {
        [$user, $employee] = $this->employeeWithUser();
        $this->actingAs($user);

        $response = $this->post(route('employee.leave-applications.store'), [
            'leave_type' => 'Sick Leave',
            'date_from' => '2026-03-01',
            'date_to' => '2026-03-03',
            'total_days' => 3,
            'reason' => 'Medical appointment',
        ]);

        $response->assertRedirect(route('employee.leave-applications.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leave_applications', [
            'employee_id' => $employee->id,
            'type' => 'Sick Leave',
            'total_days' => 3,
            'status' => 'pending',
        ]);
    }

    public function test_employee_cannot_submit_leave_with_invalid_data(): void
    {
        [$user] = $this->employeeWithUser();
        $this->actingAs($user);

        $response = $this->from(route('employee.leave-applications.index'))
            ->post(route('employee.leave-applications.store'), [
                'leave_type' => '', // required
                'date_from' => '',  // required
                'total_days' => 0,  // min:0.5
            ]);

        $response->assertSessionHasErrors(['leave_type', 'date_from', 'total_days']);
    }

    // ── Employee Leave Update ───────────────────────

    public function test_employee_can_update_leave_application(): void
    {
        [$user, $employee] = $this->employeeWithUser();
        $this->actingAs($user);

        $leave = LeaveApplication::factory()->pending()->create([
            'employee_id' => $employee->id,
        ]);

        $response = $this->put(route('employee.leave-applications.update', $leave->id), [
            'leave_type' => 'Emergency Leave',
            'date_from' => '2026-04-01',
            'total_days' => 1,
            'reason' => 'Updated reason',
        ]);

        $response->assertRedirect(route('employee.leave-applications.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leave_applications', [
            'id' => $leave->id,
            'type' => 'Emergency Leave',
            'reason' => 'Updated reason',
        ]);
    }

    // ── Employee Leave Delete ───────────────────────

    public function test_employee_can_delete_leave_application(): void
    {
        [$user, $employee] = $this->employeeWithUser();
        $this->actingAs($user);

        $leave = LeaveApplication::factory()->pending()->create([
            'employee_id' => $employee->id,
        ]);

        $response = $this->delete(route('employee.leave-applications.destroy', $leave->id));

        $response->assertRedirect(route('employee.leave-applications.index'));
        $this->assertDatabaseMissing('leave_applications', ['id' => $leave->id]);
    }

    // ── HR Leave Index ──────────────────────────────

    public function test_hr_can_view_all_leave_applications(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        // Create employees with leaves
        $emp1 = Employee::factory()->create();
        $emp2 = Employee::factory()->create();
        LeaveApplication::factory()->create(['employee_id' => (string) $emp1->id]);
        LeaveApplication::factory()->create(['employee_id' => (string) $emp2->id]);

        $response = $this->get(route('hr.leave-applications.index'));
        $response->assertStatus(200);
    }

    // ── HR Leave Store ──────────────────────────────

    public function test_hr_can_create_leave_for_employee(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        $employee = Employee::factory()->create();

        $response = $this->post(route('hr.leave-applications.store'), [
            'employee_id' => (string) $employee->id,
            'leave_type' => 'Vacation Leave',
            'date_from' => '2026-03-10',
            'total_days' => 2,
            'reason' => 'Annual vacation',
        ]);

        $response->assertRedirect(route('hr.leave-applications.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leave_applications', [
            'employee_id' => (string) $employee->id,
            'type' => 'Vacation Leave',
            'status' => 'pending',
        ]);
    }

    // ── HR Leave Update (status change) ─────────────

    public function test_hr_can_update_leave_status(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        $employee = Employee::factory()->create();
        $leave = LeaveApplication::factory()->pending()->create([
            'employee_id' => (string) $employee->id,
        ]);

        $response = $this->put(route('hr.leave-applications.update', $leave->id), [
            'employee_id' => (string) $employee->id,
            'status' => 'approved',
            'leave_type' => $leave->type,
            'date_from' => $leave->date_from->toDateString(),
            'total_days' => $leave->total_days,
            'reason' => $leave->reason,
        ]);

        $response->assertRedirect(route('hr.leave-applications.index'));

        $this->assertDatabaseHas('leave_applications', [
            'id' => $leave->id,
            'status' => 'approved',
        ]);
    }

    // ── HR Leave Delete ─────────────────────────────

    public function test_hr_can_delete_leave_application(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        $leave = LeaveApplication::factory()->create([
            'employee_id' => (string) Employee::factory()->create()->id,
        ]);

        $response = $this->delete(route('hr.leave-applications.destroy', $leave->id));

        $response->assertRedirect(route('hr.leave-applications.index'));
        $this->assertDatabaseMissing('leave_applications', ['id' => $leave->id]);
    }

    // ── HR Leave Export ─────────────────────────────

    public function test_hr_can_export_leave_csv(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        LeaveApplication::factory()->count(3)->create([
            'employee_id' => (string) Employee::factory()->create()->id,
        ]);

        $response = $this->get(route('hr.leave-applications.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
