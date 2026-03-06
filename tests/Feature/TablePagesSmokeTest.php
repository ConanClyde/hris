<?php

declare(strict_types=1);

use App\Features\Users\Enums\UserRole;
use App\Models\User;

it('renders admin user management index', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $this->actingAs($admin)
        ->get(route('admin.users'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Admin/Users/Index'));
});

it('renders hr user management index', function (): void {
    $hr = User::factory()->create(['role' => UserRole::Hr->value]);

    $this->actingAs($hr)
        ->get(route('hr.users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('HR/Users/Index'));
});
