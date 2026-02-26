<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Training\Models\Training;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingCrudTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ─────────────────────────────────────

    private function trainingData(array $overrides = []): array
    {
        return array_merge([
            'type' => 'Technical',
            'category' => 'Internal',
            'title' => 'Laravel Advanced Workshop',
            'provider' => 'Acme Corp',
            'date_from' => '2026-03-01',
            'date_to' => '2026-03-03',
            'time_from' => '09:00',
            'time_to' => '17:00',
            'hours' => 24,
            'fee' => 5000,
            'participants' => 'Team A',
        ], $overrides);
    }

    // ── Auth Guards ─────────────────────────────────

    public function test_guest_cannot_access_employee_training_index(): void
    {
        $response = $this->get(route('employee.training.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_employee_cannot_access_hr_training(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->get(route('hr.training.index'));
        $response->assertRedirect(route('dashboard'));
    }

    // ── Employee Training Index ─────────────────────

    public function test_employee_can_view_training_index(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->get(route('employee.training.index'));
        $response->assertStatus(200);
    }

    // ── Employee Training Store ─────────────────────

    public function test_employee_can_submit_training_record(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->post(route('employee.training.store'), $this->trainingData());

        $response->assertRedirect(route('employee.training.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trainings', [
            'title' => 'Laravel Advanced Workshop',
            'type' => 'Technical',
            'status' => 'pending',
        ]);
    }

    public function test_employee_cannot_submit_invalid_training(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->from(route('employee.training.index'))
            ->post(route('employee.training.store'), [
                'type' => '',    // required
                'title' => '',   // required
                'date_from' => '', // required
                'date_to' => '',   // required
            ]);

        $response->assertSessionHasErrors(['type', 'title', 'date_from', 'date_to']);
    }

    // ── Employee Training Update ────────────────────

    public function test_employee_can_update_training_record(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $training = Training::create($this->trainingData([
            'employee_id' => 'EMP-001',
            'status' => 'pending',
        ]));

        $response = $this->put(
            route('employee.training.update', $training->id),
            $this->trainingData(['title' => 'Updated Workshop Title'])
        );

        $response->assertRedirect(route('employee.training.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trainings', [
            'id' => $training->id,
            'title' => 'Updated Workshop Title',
        ]);
    }

    // ── Employee Training Delete ────────────────────

    public function test_employee_can_delete_training_record(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $training = Training::create($this->trainingData([
            'employee_id' => 'EMP-001',
            'status' => 'pending',
        ]));

        $response = $this->delete(route('employee.training.destroy', $training->id));

        $response->assertRedirect(route('employee.training.index'));
        $this->assertDatabaseMissing('trainings', ['id' => $training->id]);
    }

    // ── HR Training Index ───────────────────────────

    public function test_hr_can_view_all_training_records(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        Training::create($this->trainingData(['employee_id' => 'EMP-001', 'status' => 'pending']));
        Training::create($this->trainingData(['employee_id' => 'EMP-002', 'title' => 'Another Training', 'status' => 'approved']));

        $response = $this->get(route('hr.training.index'));
        $response->assertStatus(200);
    }

    // ── HR Training Store ───────────────────────────

    public function test_hr_can_create_training_for_employee(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        $employee = Employee::factory()->create();

        $response = $this->post(route('hr.training.store'), $this->trainingData([
            'employee_id' => 'EMP-'.$employee->id,
            'status' => 'approved',
        ]));

        $response->assertRedirect(route('hr.training.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trainings', [
            'employee_id' => 'EMP-'.$employee->id,
            'title' => 'Laravel Advanced Workshop',
            'status' => 'approved',
        ]);
    }

    // ── HR Training Update ──────────────────────────

    public function test_hr_can_update_training_record(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        $employee = Employee::factory()->create();
        $training = Training::create($this->trainingData([
            'employee_id' => 'EMP-'.$employee->id,
            'status' => 'pending',
        ]));

        $response = $this->put(
            route('hr.training.update', $training->id),
            $this->trainingData([
                'employee_id' => 'EMP-'.$employee->id,
                'title' => 'Updated by HR',
                'status' => 'approved',
            ])
        );

        $response->assertRedirect(route('hr.training.index'));

        $this->assertDatabaseHas('trainings', [
            'id' => $training->id,
            'employee_id' => 'EMP-'.$employee->id,
            'title' => 'Updated by HR',
            'status' => 'approved',
        ]);
    }

    // ── HR Training Delete ──────────────────────────

    public function test_hr_can_delete_training_record(): void
    {
        $hrUser = User::factory()->create(['role' => 'hr']);
        $this->actingAs($hrUser);

        $training = Training::create($this->trainingData([
            'employee_id' => 'EMP-001',
            'status' => 'pending',
        ]));

        $response = $this->delete(route('hr.training.destroy', $training->id));

        $response->assertRedirect(route('hr.training.index'));
        $this->assertDatabaseMissing('trainings', ['id' => $training->id]);
    }
}
