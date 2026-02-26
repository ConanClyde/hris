<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsSessionsRevokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_revoke_session_sets_revoked_sessions_in_session(): void
    {
        $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->post(route('settings.sessions.revoke'), [
            '_token' => csrf_token(),
            'session_id' => 'mock-session-2',
        ])->assertSessionHas('success');

        $revoked = session('revoked_sessions', []);
        $this->assertContains('mock-session-2', $revoked);
    }
}
