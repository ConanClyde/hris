<?php

namespace App\Features\Backup\Console\Commands;

use App\Features\Backup\Jobs\RunBackupJob;
use App\Features\Backup\Models\Backup;
use Illuminate\Console\Command;

class RunBackupCommand extends Command
{
    protected $signature = 'backups:run {--notes=}';

    protected $description = 'Queue a database backup.';

    public function handle(): int
    {
        $disk = (string) config('backup.disk', 'local');
        $dir = trim((string) config('backup.path', 'backups'), '/');

        $filename = 'backup-'.now()->format('Ymd_His').'.sql';
        $path = $dir.'/'.$filename;

        $backup = Backup::query()->create([
            'filename' => $filename,
            'disk' => $disk,
            'path' => $path,
            'size_bytes' => 0,
            'checksum' => null,
            'created_by_user_id' => null,
            'status' => 'pending',
            'completed_at' => null,
            'notes' => $this->option('notes') ?: null,
        ]);

        RunBackupJob::dispatch($backup->id);

        $this->info('Backup queued: #'.$backup->id);

        return self::SUCCESS;
    }
}
