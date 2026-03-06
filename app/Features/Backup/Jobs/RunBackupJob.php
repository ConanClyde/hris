<?php

namespace App\Features\Backup\Jobs;

use App\Features\Backup\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $backupId) {}

    public function handle(): void
    {
        /** @var Backup $backup */
        $backup = Backup::query()->findOrFail($this->backupId);

        $disk = $backup->disk ?: config('backup.disk', 'local');
        $path = $backup->path;

        $tmpDir = storage_path('app/tmp');
        if (! is_dir($tmpDir)) {
            @mkdir($tmpDir, 0755, true);
        }

        $tmpFile = $tmpDir.DIRECTORY_SEPARATOR.Str::uuid()->toString().'.sql';

        try {
            $this->dumpDatabase($tmpFile);

            $checksum = hash_file('sha256', $tmpFile) ?: null;
            $size = filesize($tmpFile) ?: 0;

            Storage::disk($disk)->put($path, file_get_contents($tmpFile));

            $backup->forceFill([
                'checksum' => $checksum,
                'size_bytes' => (int) $size,
                'status' => 'completed',
                'completed_at' => now(),
            ])->save();
        } catch (\Throwable $e) {
            $backup->forceFill([
                'status' => 'failed',
                'completed_at' => now(),
            ])->save();

            throw $e;
        } finally {
            if (is_file($tmpFile)) {
                @unlink($tmpFile);
            }
        }
    }

    protected function dumpDatabase(string $outputFile): void
    {
        $driver = (string) config('database.default');
        $config = config("database.connections.{$driver}");

        if (! is_array($config)) {
            throw new RuntimeException('Database connection config not found.');
        }

        if (($config['driver'] ?? null) !== 'mysql') {
            throw new RuntimeException('Only mysql backups are currently supported.');
        }

        $database = (string) ($config['database'] ?? '');
        $host = (string) ($config['host'] ?? '127.0.0.1');
        $port = (string) ($config['port'] ?? '3306');
        $username = (string) ($config['username'] ?? '');
        $password = (string) ($config['password'] ?? '');

        if ($database === '' || $username === '') {
            throw new RuntimeException('Missing database credentials.');
        }

        $passwordPart = $password !== '' ? "-p{$password}" : '';

        $command = "mysqldump --single-transaction --quick --skip-lock-tables --routines --triggers --host={$host} --port={$port} --user={$username} {$passwordPart} {$database} > \"{$outputFile}\"";

        $exitCode = null;
        @system($command, $exitCode);

        if ($exitCode !== 0 || ! is_file($outputFile)) {
            throw new RuntimeException('Database dump failed. Ensure mysqldump is installed and accessible.');
        }

        $ping = DB::select('select 1 as ok');
        if (! is_array($ping)) {
            throw new RuntimeException('Database connection check failed after dump.');
        }
    }
}
