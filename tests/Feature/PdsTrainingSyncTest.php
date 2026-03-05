<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsTraining;
use App\Features\Training\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdsTrainingSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_pds_index_syncs_approved_trainings_only_and_orders_latest_first(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        $pds = Pds::create([
            'employee_id' => $employee->id,
            'status' => 'draft',
        ]);

        Training::create([
            'employee_id' => (string) $employee->id,
            'employee_fk' => $employee->id,
            'employee_name' => $employee->full_name,
            'title' => 'Old Approved',
            'date_from' => now()->subDays(20)->toDateString(),
            'date_to' => now()->subDays(19)->toDateString(),
            'hours' => 8,
            'type' => 'Technical',
            'provider' => 'Provider A',
            'status' => 'approved',
        ]);

        Training::create([
            'employee_id' => (string) $employee->id,
            'employee_fk' => $employee->id,
            'employee_name' => $employee->full_name,
            'title' => 'New Approved',
            'date_from' => now()->subDays(5)->toDateString(),
            'date_to' => now()->subDays(4)->toDateString(),
            'hours' => 16,
            'type' => 'Leadership',
            'provider' => 'Provider B',
            'status' => 'approved',
        ]);

        Training::create([
            'employee_id' => (string) $employee->id,
            'employee_fk' => $employee->id,
            'employee_name' => $employee->full_name,
            'title' => 'Pending Not Included',
            'date_from' => now()->subDays(2)->toDateString(),
            'date_to' => now()->subDays(1)->toDateString(),
            'hours' => 4,
            'type' => 'Other',
            'provider' => 'Provider C',
            'status' => 'pending',
        ]);

        $this->actingAs($user)->get('/employee/pds')->assertOk();

        $rows = PdsTraining::query()
            ->where('pds_id', $pds->id)
            ->orderBy('sort_order')
            ->get();

        $this->assertCount(2, $rows);
        $this->assertSame('New Approved', $rows[0]->title);
        $this->assertSame('Old Approved', $rows[1]->title);
    }
}
