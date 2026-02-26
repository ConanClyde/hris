<?php

namespace App\Features\Backup\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    private string $disk = 'local';

    private string $directory = 'backups';

    public function index()
    {
        $files = Storage::disk($this->disk)->exists($this->directory)
            ? Storage::disk($this->disk)->files($this->directory)
            : [];

        return view('features.backup.admin.index', [
            'files' => $files,
            'canManageBackup' => true,
            'backupRoutePrefix' => 'admin',
        ]);
    }

    public function run(Request $request)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        Storage::disk($this->disk)->makeDirectory($this->directory);

        $filename = 'backup_'.now()->format('Ymd_His').'_'.Str::random(6).'.txt';
        $path = $this->directory.'/'.$filename;

        Storage::disk($this->disk)->put($path, 'Mock backup generated at '.now()->toDateTimeString());

        return redirect()->route('admin.backup.index')->with('success', 'Backup created.');
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'backup' => ['required', 'file', 'max:51200'],
        ]);

        Storage::disk($this->disk)->makeDirectory($this->directory);

        $file = $validated['backup'];
        $filename = 'upload_'.now()->format('Ymd_His').'_'.Str::random(6).'_'.preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $file->storeAs($this->directory, $filename, $this->disk);

        return redirect()->route('admin.backup.index')->with('success', 'Backup uploaded.');
    }

    public function download(string $id)
    {
        $path = $this->resolvePathFromId($id);
        if (! $path || ! Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        return Storage::disk($this->disk)->download($path);
    }

    public function restore(Request $request, string $id)
    {
        $path = $this->resolvePathFromId($id);
        if (! $path || ! Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        return redirect()->route('admin.backup.index')->with('success', 'Backup restored (mock).');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $path = $this->resolvePathFromId($id);
        if (! $path || ! Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        return redirect()->route('admin.backup.index')->with('success', 'Backup updated (mock).');
    }

    public function destroy(string $id)
    {
        $path = $this->resolvePathFromId($id);
        if (! $path || ! Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        Storage::disk($this->disk)->delete($path);

        return redirect()->route('admin.backup.index')->with('success', 'Backup deleted.');
    }

    private function resolvePathFromId(string $id): ?string
    {
        $files = Storage::disk($this->disk)->exists($this->directory)
            ? Storage::disk($this->disk)->files($this->directory)
            : [];

        foreach ($files as $path) {
            if (sha1($path) === $id) {
                return $path;
            }
        }

        return null;
    }
}
