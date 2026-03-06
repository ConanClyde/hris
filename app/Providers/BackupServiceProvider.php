<?php

namespace App\Providers;

use App\Features\Backup\Console\Commands\PruneBackupsCommand;
use App\Features\Backup\Console\Commands\RunBackupCommand;
use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunBackupCommand::class,
                PruneBackupsCommand::class,
            ]);
        }
    }
}
