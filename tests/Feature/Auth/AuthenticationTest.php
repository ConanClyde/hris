<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Features;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'username' => $user->username,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_secret' => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $response = $this->post(route('login'), [
        'username' => $user->username,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $response->assertSessionHas('login.id', $user->id);
    $this->assertGuest();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'username' => $user->username,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(route('home'));
});

test('users are rate limited', function () {
    $user = User::factory()->create();

    RateLimiter::increment(md5('login'.implode('|', [$user->username, '127.0.0.1'])), amount: 5);

    $response = $this->post(route('login.store'), [
        'username' => $user->username,
        'password' => 'wrong-password',
    ]);

    $response->assertTooManyRequests();
});

test('hr users can access activity logs', function () {
    $user = User::factory()->create(['role' => 'hr']);

    $response = $this->actingAs($user)->get('/hr/activity-logs');

    $response->assertOk();
});

test('employees can access their activity logs', function () {
    $user = User::factory()->create(['role' => 'employee']);

    $response = $this->actingAs($user)->get('/employee/activity-logs');

    $response->assertOk();
});

test('employees cannot access hr activity logs', function () {
    $user = User::factory()->create(['role' => 'employee']);

    $response = $this->actingAs($user)->get('/hr/activity-logs');

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('password reset requests are rate limited', function () {
    Mail::fake();
    $user = User::factory()->create(['email' => 'reset@example.com']);

    for ($i = 0; $i < 5; $i++) {
        $this->post('/forgot-password', ['email' => $user->email]);
    }

    $response = $this->post('/forgot-password', ['email' => $user->email]);

    $response->assertTooManyRequests();
});
