<?php

use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

test('index returns only current user notifications', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $other = User::factory()->create();

    $user->notify(new SystemNotification(
        type: 'info',
        title: 'My notification',
        message: 'Content',
        data: ['redirect_url' => '/admin/notifications'],
    ));

    $other->notify(new SystemNotification(
        type: 'info',
        title: 'Other notification',
        message: 'Other',
        data: ['redirect_url' => '/admin/notifications'],
    ));

    $mine = $user->notifications()->firstOrFail();

    $this->actingAs($user)
        ->get(route('admin.notifications'))
        ->assertOk()
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Admin/Notifications/Index')
            ->has('notifications.data', 1)
            ->where('notifications.data.0.id', (string) $mine->id)
        );
});

test('markAsRead sets read_at only for owner notifications', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $other = User::factory()->create();

    $user->notify(new SystemNotification(type: 'info', title: 'Mine', message: 'A'));
    $other->notify(new SystemNotification(type: 'info', title: 'Foreign', message: 'B'));

    $notification = $user->notifications()->firstOrFail();
    $foreign = $other->notifications()->firstOrFail();

    $this->actingAs($user)
        ->post(route('admin.notifications.mark-as-read', $notification->id))
        ->assertOk();

    $notification->refresh();
    $foreign->refresh();

    expect($notification->read_at)->not->toBeNull();
    expect($foreign->read_at)->toBeNull();
});

test('unreadCount returns correct integer', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $user->notify(new SystemNotification(type: 'info', title: 'Unread', message: 'X'));
    $user->notify(new SystemNotification(type: 'info', title: 'Read', message: 'Y'));
    $user->notifications()->firstOrFail()->markAsRead();

    $response = $this->actingAs($user)->get('/admin/notifications/unread-count');

    $response->assertOk();
    $response->assertJson(['count' => 1]);
});

test('index returns JSON for dropdown when expectsJson and only=notifications', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $user->notify(new SystemNotification(type: 'info', title: 'Dropdown', message: 'Content'));

    $response = $this->actingAs($user)
        ->getJson('/admin/notifications?only=notifications');

    $response->assertOk();
    $response->assertJsonStructure([
        'props' => [
            'notifications' => [
                'data' => [
                    '*' => ['id', 'title', 'body', 'type', 'is_read', 'created_at'],
                ],
            ],
        ],
    ]);
    expect($response->json('props.notifications.data'))->toHaveCount(1);
});

test('index can filter unread only', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $user->notify(new SystemNotification(type: 'info', title: 'Unread', message: 'X'));
    $user->notify(new SystemNotification(type: 'info', title: 'Read', message: 'Y'));

    $read = $user->notifications()->firstOrFail();
    $read->markAsRead();

    $this->actingAs($user)
        ->get('/admin/notifications?filter=unread')
        ->assertOk()
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->has('notifications.data', 1)
            ->where('filter', 'unread')
        );
});

test('markAllRead marks all unread notifications as read', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $user->notify(new SystemNotification(type: 'info', title: 'A', message: 'A'));
    $user->notify(new SystemNotification(type: 'info', title: 'B', message: 'B'));

    $this->actingAs($user)
        ->post('/admin/notifications/mark-all-read')
        ->assertOk();

    expect($user->fresh()->unreadNotifications()->count())->toBe(0);
});

test('delete endpoints remove notifications only for owner', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $other = User::factory()->create();

    $user->notify(new SystemNotification(type: 'info', title: 'Mine', message: 'A'));
    $other->notify(new SystemNotification(type: 'info', title: 'Foreign', message: 'B'));

    $mine = $user->notifications()->firstOrFail();
    $foreign = $other->notifications()->firstOrFail();

    $this->actingAs($user)
        ->delete("/admin/notifications/{$mine->id}")
        ->assertOk();

    expect($user->fresh()->notifications()->count())->toBe(0);
    expect($other->fresh()->notifications()->count())->toBe(1);

    $this->actingAs($user)
        ->delete('/admin/notifications')
        ->assertOk();

    expect($other->fresh()->notifications()->count())->toBe(1);
    expect($foreign->fresh()->exists)->toBeTrue();
});

test('SystemNotification broadcasts immediately', function () {
    expect(SystemNotification::class)->toImplement(ShouldBroadcastNow::class);
});
