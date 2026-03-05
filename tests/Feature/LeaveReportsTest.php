<?php

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Models\User;

test('hr leave report pages render without sql errors', function () {
    $hr = User::factory()->create(['role' => 'hr']);
    $employeeUser = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create(['user_id' => $employeeUser->id]);

    LeaveApplication::factory()->create([
        'employee_fk' => $employee->id,
        'employee_id' => $employee->id,
        'employee_name' => $employee->full_name,
        'status' => 'approved',
        'date_from' => now()->startOfYear(),
        'total_days' => 1,
    ]);

    $this->actingAs($hr)
        ->get(route('hr.reports.leave', ['year' => now()->year]))
        ->assertOk()
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('HR/Reports/Leave')
            ->has('usageRows')
        );

    $this->actingAs($hr)
        ->get(route('hr.reports.leave.export', ['year' => now()->year]))
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=utf-8');
});
