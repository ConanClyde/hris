<?php

namespace App\Features\Backup\Console\Commands;

use App\Features\Backup\Models\Backup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneBackupsCommand extends Command
{
    protected $signature = 'backups:prune';

    protected $description = 'Prune old backups based on retention policy.';

    public function handle(): int
    {
        $keepLast = (int) config('backup.retention.keep_last', 10);
        if ($keepLast <= 0) {
            $this->info('Retention disabled (keep_last <= 0).');

            return self::SUCCESS;
        }

        $idsToKeep = Backup::query()
            ->orderByDesc('created_at')
            ->limit($keepLast)
            ->pluck('id')
            ->all();

        $toPrune = Backup::query()
            ->whereNotIn('id', $idsToKeep)
            ->orderBy('created_at')
            ->get();

        $deleted = 0;
        foreach ($toPrune as $backup) {
            $disk = $backup->disk ?? config('backup.disk', 'local');
            $path = $backup->path;

            if (is_string($path) && $path !== '' && Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }

            $backup->delete();
            $deleted++;
        }

        $this->info("Pruned {$deleted} backups.");

        return self::SUCCESS;
    }
}
