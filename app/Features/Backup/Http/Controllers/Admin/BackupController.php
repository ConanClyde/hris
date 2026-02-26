<?php

namespace App\Features\Backup\Http\Controllers\Admin;

use App\Features\Backup\Models\Backup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $backups = Backup::orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery);

        return Inertia::render('Admin/Backup/Index', ['backups' => $backups]);
    }

    public function run(Request $request)
    {
        // Trigger backup process
        return redirect()->route('admin.backup.index')->with('success', 'Backup initiated.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:102400',
        ]);

        return redirect()->route('admin.backup.index')->with('success', 'Backup uploaded.');
    }

    public function download($id)
    {
        $backup = Backup::findOrFail($id);

        return response()->download(storage_path('app/backups/'.$backup->filename));
    }

    public function restore($id)
    {
        $backup = Backup::findOrFail($id);

        return redirect()->route('admin.backup.index')->with('success', 'Restore initiated.');
    }

    public function update(Request $request, $id)
    {
        $backup = Backup::findOrFail($id);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $backup->update($validated);

        return redirect()->route('admin.backup.index')->with('success', 'Backup updated.');
    }

    public function destroy($id)
    {
        Backup::findOrFail($id)->delete();

        return redirect()->route('admin.backup.index')->with('success', 'Backup deleted.');
    }
}
