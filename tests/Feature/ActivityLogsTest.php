<?php

use App\Features\ActivityLogs\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Inertia\Testing\AssertableInertia as Assert;

test('logs update actions via middleware for authenticated users', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->from(route('admin.profile'))
        ->patch(route('admin.profile.update'), [
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'email' => $admin->email,
        ])
        ->assertRedirect(route('admin.profile'));

    $log = ActivityLog::query()
        ->where('actor_user_id', $admin->id)
        ->where('action', 'update')
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log?->metadata)->toBeArray();
    expect($log?->metadata['route_name'] ?? null)->toBe('admin.profile.update');
});

test('logs login and logout events', function () {
    $user = User::factory()->create();

    event(new Login('web', $user, false));
    event(new Logout('web', $user));

    expect(ActivityLog::query()
        ->where('actor_user_id', $user->id)
        ->where('action', 'login')
        ->exists())->toBeTrue();

    expect(ActivityLog::query()
        ->where('actor_user_id', $user->id)
        ->where('action', 'logout')
        ->exists())->toBeTrue();
});

test('admin activity logs index supports search and action filters', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $other = User::factory()->create(['name' => 'Other User']);

    ActivityLog::create([
        'actor_user_id' => $admin->id,
        'role' => $admin->role,
        'action' => 'delete',
        'metadata' => ['description' => 'delete admin.users.destroy'],
        'ip_address' => '127.0.0.1',
        'user_agent' => 'test',
    ]);

    ActivityLog::create([
        'actor_user_id' => $admin->id,
        'role' => $admin->role,
        'action' => 'update',
        'metadata' => ['description' => 'update admin.profile.update'],
        'ip_address' => '127.0.0.1',
        'user_agent' => 'test',
    ]);

    ActivityLog::create([
        'actor_user_id' => $other->id,
        'role' => $other->role,
        'action' => 'delete',
        'metadata' => ['description' => 'delete something'],
        'ip_address' => '127.0.0.1',
        'user_agent' => 'test',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.activity-logs.index', ['search' => $admin->name, 'action' => 'delete']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/ActivityLogs/Index')
            ->where('filters.action', 'delete')
            ->where('filters.search', $admin->name)
            ->has('logs.data', 1)
            ->where('logs.data.0.action', 'delete')
            ->where('logs.data.0.user_id', $admin->id)
        );
});
