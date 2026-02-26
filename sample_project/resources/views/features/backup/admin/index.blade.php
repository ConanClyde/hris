@php
    echo view('admin.backup.index', [
        'files' => $files ?? null,
        'canManageBackup' => $canManageBackup ?? false,
        'backupRoutePrefix' => $backupRoutePrefix ?? 'admin',
    ])->render();
@endphp
