<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_backup_index(): void
    {
        Storage::fake('local');

        $response = $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->get(route('admin.backup.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_run_backup_and_then_delete_it(): void
    {
        Storage::fake('local');

        $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->post(route('admin.backup.run'), ['_token' => csrf_token()])
            ->assertRedirect(route('admin.backup.index'));

        $files = Storage::disk('local')->files('backups');
        $this->assertNotEmpty($files);

        $id = sha1($files[0]);

        $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->delete(route('admin.backup.destroy', ['id' => $id]), ['_token' => csrf_token()])
            ->assertRedirect(route('admin.backup.index'));

        $this->assertCount(0, Storage::disk('local')->files('backups'));
    }
}
