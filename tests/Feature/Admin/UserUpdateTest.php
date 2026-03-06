<?php

use App\Features\Users\Enums\UserRole;
use App\Models\User;

test('hr can update employee and toggle is_active using boolean payload', function (): void {
    $hr = User::factory()->create([
        'role' => UserRole::Hr->value,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $employee = User::factory()->create([
        'role' => UserRole::Employee->value,
        'status' => 'approved',
        'is_active' => true,
        'first_name' => 'Old',
        'last_name' => 'Name',
        'username' => 'oldusername',
        'email' => 'old@example.com',
        'name' => 'Old Name',
    ]);

    $this->actingAs($hr);

    $this->put(route('hr.users.update', $employee->id), [
        'name' => 'New Name',
        'username' => 'newusername',
        'email' => 'new@example.com',
        'is_active' => false,
        'first_name' => 'New',
        'middle_name' => null,
        'last_name' => 'Name',
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $employee->refresh();

    expect($employee->name)->toBe('New Name');
    expect($employee->username)->toBe('newusername');
    expect($employee->email)->toBe('new@example.com');
    expect($employee->is_active)->toBeFalse();
    expect($employee->first_name)->toBe('New');
});
