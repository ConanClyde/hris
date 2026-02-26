<?php

use App\Models\User;

test('registration screen can be rendered by guest', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('guest can register new user and is redirected to login', function () {
    $response = $this->post(route('register.store'), [
        'role' => 'employee',
        'first_name' => 'Test',
        'middle_name' => null,
        'surname' => 'User',
        'name_extension' => null,
        'sex' => 'male',
        'date_of_birth' => '1990-01-01',
        'date_hired' => null,
        'division_id' => null,
        'subdivision_id' => null,
        'section_id' => null,
        'position' => null,
        'classification' => null,
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('login', absolute: false));
    $response->assertSessionHas('status', 'Registration submitted. Your account is pending admin approval.');

    $this->assertDatabaseHas('users', [
        'username' => 'testuser',
        'email' => 'test@example.com',
        'is_active' => false,
    ]);
});

test('inactive user cannot log in', function () {
    $user = User::factory()->create([
        'username' => 'pendinguser',
        'email' => 'pending@example.com',
        'is_active' => false,
    ]);

    $response = $this->post(route('login.store'), [
        'username' => 'pendinguser',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});
