<?php

namespace App\Features\Notices\Http\Controllers\HR;

use App\Features\Notices\Models\Notice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $notices = Notice::orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        return Inertia::render('HR/Notices/Index', ['notices' => $notices]);
    }

    public function create()
    {
        return redirect()->route('hr.notices.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|in:info,success,warning,danger',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        Notice::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'] ?? 'info',
            'is_active' => $validated['is_active'] ?? true,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return redirect()->route('hr.notices.index')->with('success', 'Notice created.');
    }

    public function edit($id)
    {
        return redirect()->route('hr.notices.index');
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|in:info,success,warning,danger',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $notice->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'] ?? 'info',
            'is_active' => $validated['is_active'] ?? true,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return redirect()->route('hr.notices.index')->with('success', 'Notice updated.');
    }

    public function destroy($id)
    {
        Notice::findOrFail($id)->delete();

        return redirect()->route('hr.notices.index')->with('success', 'Notice deleted.');
    }
}
