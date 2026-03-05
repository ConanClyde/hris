<?php

use App\Features\Users\Enums\UserRole;
use App\Models\User;

test('user flagged to change password is redirected to force-change-password page', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin->value,
        'must_change_password' => true,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('force-password.show'));
});

test('user without must_change_password can access dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin->value,
        'must_change_password' => false,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertRedirect();
});
