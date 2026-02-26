<?php

namespace Tests\Unit;

use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserEmployeeRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_employee_relationship(): void
    {
        $user = User::factory()->create();
        $this->assertTrue($user->relationLoaded('employee') === false || method_exists($user, 'employee'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $user->employee());
    }

    public function test_user_returns_null_employee_when_none_exists(): void
    {
        $user = User::factory()->create();
        $this->assertNull($user->employee);
    }

    public function test_user_returns_employee_when_exists(): void
    {
        $user = User::factory()->create();
        $employee = Employee::create([
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $user->email,
            'status' => 'active',
        ]);

        $user->load('employee');
        $this->assertNotNull($user->employee);
        $this->assertInstanceOf(Employee::class, $user->employee);
        $this->assertEquals($employee->id, $user->employee->id);
    }

    public function test_user_with_returns_employee_eager_loaded(): void
    {
        $user = User::factory()->create();
        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => $user->email,
            'status' => 'active',
        ]);

        $loadedUser = User::with('employee')->find($user->id);
        $this->assertTrue($loadedUser->relationLoaded('employee'));
        $this->assertNotNull($loadedUser->employee);
    }
}
