<?php

namespace Tests\Feature;

use App\Features\Notices\Models\Notice;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiEndpointTest extends TestCase
{
    use RefreshDatabase;

    // ── /api/me ─────────────────────────────────────

    public function test_guest_cannot_access_api_me(): void
    {
        $response = $this->getJson('/api/me');

        // session.auth middleware redirects, but JSON request gets 401
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'employee',
            'email' => 'jane@example.com',
        ]);
        $this->actingAs($user);

        $response = $this->getJson('/api/me');

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'display_name',
            'email',
            'role',
            'initial',
        ]);
        $response->assertJsonFragment(['role' => 'employee']);
    }

    // ── /api/notifications ──────────────────────────

    public function test_guest_cannot_access_notifications_api(): void
    {
        $response = $this->getJson('/api/notifications');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_notifications(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Notice::factory()->count(3)->create(['is_active' => true]);

        $response = $this->getJson('/api/notifications');

        $response->assertOk();
        $response->assertJsonStructure([
            'items',
            'unread_count',
        ]);
        $this->assertCount(3, $response->json('items'));
        $this->assertEquals(3, $response->json('unread_count'));
    }

    public function test_notifications_include_is_read_flag(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $readNotice = Notice::factory()->create(['is_active' => true]);
        $unreadNotice = Notice::factory()->create(['is_active' => true]);

        // Mark one as read
        DB::table('notice_reads')->insert([
            'user_id' => $user->id,
            'notice_id' => $readNotice->id,
            'read_at' => now(),
        ]);

        $response = $this->getJson('/api/notifications');
        $response->assertOk();

        $items = collect($response->json('items'));
        $readItem = $items->firstWhere('id', $readNotice->id);
        $unreadItem = $items->firstWhere('id', $unreadNotice->id);

        $this->assertTrue($readItem['is_read']);
        $this->assertFalse($unreadItem['is_read']);
        $this->assertEquals(1, $response->json('unread_count'));
    }

    // ── Mark Read ───────────────────────────────────

    public function test_user_can_mark_all_notifications_read(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Notice::factory()->count(3)->create(['is_active' => true]);

        $response = $this->postJson('/api/notifications/mark-read');
        $response->assertOk();
        $response->assertJsonFragment(['success' => true]);

        // Verify all are now read
        $checkResponse = $this->getJson('/api/notifications');
        $this->assertEquals(0, $checkResponse->json('unread_count'));
    }

    public function test_user_can_mark_single_notification_read(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $notice = Notice::factory()->create(['is_active' => true]);

        $response = $this->postJson("/api/notifications/{$notice->id}/read");
        $response->assertOk();
        $response->assertJsonFragment(['success' => true]);

        // Verify the notice_reads record exists
        $this->assertDatabaseHas('notice_reads', [
            'user_id' => $user->id,
            'notice_id' => $notice->id,
        ]);
    }

    // ── Filtering ───────────────────────────────────

    public function test_notifications_respect_limit_param(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Notice::factory()->count(5)->create(['is_active' => true]);

        $response = $this->getJson('/api/notifications?limit=2');
        $response->assertOk();

        $this->assertCount(2, $response->json('items'));
    }

    public function test_expired_notices_are_excluded(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Active notice
        Notice::factory()->create([
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        // Expired notice
        Notice::factory()->create([
            'is_active' => true,
            'expires_at' => now()->subDays(1),
        ]);

        $response = $this->getJson('/api/notifications');
        $response->assertOk();

        // Only the active, non-expired notice should appear
        $this->assertCount(1, $response->json('items'));
        $this->assertEquals(1, $response->json('unread_count'));
    }
}
