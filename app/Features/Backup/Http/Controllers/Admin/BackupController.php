<?php

declare(strict_types=1);

namespace App\Features\Backup\Http\Controllers\Admin;

use App\Features\Backup\DTOs\BackupViewDTO;
use App\Features\Backup\Jobs\RunBackupJob;
use App\Features\Backup\Models\Backup;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $backups = Backup::orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery)
            ->through(
                static fn (Backup $backup): array => BackupViewDTO::fromModel($backup)->toArray(),
            );

        return Inertia::render('Admin/Backup/Index', ['backups' => $backups]);
    }

    public function run(Request $request): RedirectResponse
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
            'created_by_user_id' => $request->user()?->id,
            'status' => 'pending',
            'completed_at' => null,
            'notes' => null,
        ]);

        RunBackupJob::dispatch($backup->id);

        return redirect()->route('admin.backup.index')->with('success', 'Backup queued.');
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,zip,sqlite,db|max:102400',
        ]);

        $disk = (string) config('backup.disk', 'local');
        $dir = trim((string) config('backup.path', 'backups'), '/');
        $file = $request->file('backup_file');
        if ($file === null) {
            return redirect()->route('admin.backup.index')->with('error', 'No backup file uploaded.');
        }

        $original = $file->getClientOriginalName() ?: 'backup-upload.sql';
        $safeOriginal = Str::of($original)->replace(['..', '/', '\\'], '_')->toString();
        $filename = now()->format('Ymd_His').'-'.$safeOriginal;
        $path = $dir.'/'.$filename;

        Storage::disk($disk)->putFileAs($dir, $file, $filename);

        $checksum = hash_file('sha256', (string) $file->getRealPath()) ?: null;

        Backup::query()->create([
            'filename' => $filename,
            'disk' => $disk,
            'path' => $path,
            'size_bytes' => (int) ($file->getSize() ?: 0),
            'checksum' => $checksum,
            'created_by_user_id' => $request->user()?->id,
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => 'Uploaded backup file',
        ]);

        return redirect()->route('admin.backup.index')->with('success', 'Backup uploaded.');
    }

    public function download(int $id): StreamedResponse|RedirectResponse
    {
        $backup = Backup::findOrFail($id);

        $disk = $backup->disk ?: config('backup.disk', 'local');
        $path = $backup->path;
        if (! is_string($path) || $path === '' || ! Storage::disk($disk)->exists($path)) {
            return redirect()->route('admin.backup.index')->with('error', 'Backup file not found.');
        }

        return Storage::disk($disk)->download($path, $backup->filename);
    }

    public function restore(int $id): RedirectResponse
    {
        return redirect()->route('admin.backup.index')->with('error', 'Restore is disabled for safety.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $backup = Backup::findOrFail($id);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $backup->update($validated);

        return redirect()->route('admin.backup.index')->with('success', 'Backup updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $backup = Backup::findOrFail($id);
        $disk = $backup->disk ?: config('backup.disk', 'local');
        $path = $backup->path;

        if (is_string($path) && $path !== '' && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }

        $backup->delete();

        return redirect()->route('admin.backup.index')->with('success', 'Backup deleted.');
    }
}
