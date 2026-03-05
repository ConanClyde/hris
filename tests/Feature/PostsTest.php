<?php

use App\Features\Posts\Models\Post;
use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can create a post and notifications are generated', function () {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role' => UserRole::Admin->value,
        'is_active' => true,
    ]);

    $employee = User::factory()->create([
        'role' => UserRole::Employee->value,
        'is_active' => true,
    ]);

    $hr = User::factory()->create([
        'role' => UserRole::Hr->value,
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.posts.store'), [
            'title' => 'Test Post',
            'body' => 'This is a test body.',
            'role_scope' => 'all',
            'is_pinned' => true,
        ])
        ->assertRedirect(route('admin.posts.index'));

    $post = Post::first();
    expect($post)->not()->toBeNull()
        ->and($post->title)->toBe('Test Post');

    $admin->refresh();
    $employee->refresh();
    $hr->refresh();

    // Admin creator should not receive a notification, HR + Employee should
    expect($employee->notifications)->toHaveCount(1);
    expect($hr->notifications)->toHaveCount(1);
    expect($admin->notifications)->toHaveCount(0);
});

test('employee sees only published posts scoped to their role', function () {
    /** @var User $employee */
    $employee = User::factory()->create([
        'role' => UserRole::Employee->value,
        'is_active' => true,
    ]);

    // Visible to employees
    Post::factory()->create([
        'title' => 'For everyone',
        'role_scope' => 'all',
        'is_published' => true,
    ]);

    Post::factory()->create([
        'title' => 'For employees',
        'role_scope' => 'employee',
        'is_published' => true,
    ]);

    // Not visible to employees
    Post::factory()->create([
        'title' => 'For HR only',
        'role_scope' => 'hr',
        'is_published' => true,
    ]);

    Post::factory()->create([
        'title' => 'Draft post',
        'role_scope' => 'all',
        'is_published' => false,
    ]);

    $this->actingAs($employee)
        ->get(route('employee.posts.index'))
        ->assertOk()
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Employee/Posts/Index')
            ->has('posts.data', 2)
        );
});
