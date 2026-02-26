<?php

namespace Tests\Feature;

use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileSecurityFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_change_password_requires_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($user);

        $this->withSession([
            'user_id' => $user->id,
            'role' => 'admin',
        ])->post(route('admin.profile.password'), [
            '_token' => csrf_token(),
            'current_password' => 'wrong',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertSessionHasErrors('current_password');
    }

    public function test_change_password_updates_user_password_hash(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($user);

        $this->withSession([
            'user_id' => $user->id,
            'role' => 'admin',
        ])->post(route('admin.profile.password'), [
            '_token' => csrf_token(),
            'current_password' => 'old-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertRedirect(route('admin.profile'));

        $user->refresh();
        $this->assertTrue(Hash::check('new-password-123', $user->password));
    }

    public function test_delete_account_requires_password_and_deletes_user(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('secret-password'),
        ]);

        $this->actingAs($user);

        $this->withSession([
            'user_id' => $user->id,
            'role' => 'admin',
        ])->delete(route('admin.profile.delete'), [
            '_token' => csrf_token(),
            'password' => 'secret-password',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
