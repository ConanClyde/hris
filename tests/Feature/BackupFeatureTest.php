<?php

declare(strict_types=1);

use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

it('allows admin to view backup index', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $this->actingAs($admin)
        ->get(route('admin.backup.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Backup/Index')
            ->has('backups.data')
        );
});

it('queues backup run job for admin', function (): void {
    Queue::fake();
    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $this->actingAs($admin)
        ->post(route('admin.backup.run'))
        ->assertRedirect(route('admin.backup.index'));

    Queue::assertPushed(\App\Features\Backup\Jobs\RunBackupJob::class);
});

it('allows admin to upload backup file and stores metadata', function (): void {
    Storage::fake('local');

    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $file = UploadedFile::fake()->create('backup.sql', 10, 'application/sql');

    $this->actingAs($admin)
        ->post(route('admin.backup.upload'), [
            'backup_file' => $file,
        ])
        ->assertRedirect(route('admin.backup.index'));

    $this->assertDatabaseCount('backups', 1);
});
