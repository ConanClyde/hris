<?php

namespace App\Features\Notices\Http\Controllers\Admin;

use App\Features\Notices\Events\NoticePublished;
use App\Features\Notices\Models\Notice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::latest()->paginate(10);

        return view('features.notices.admin.index', compact('notices'));
    }

    public function create()
    {
        return redirect()->route('admin.notices.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,danger',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $notice = Notice::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        Cache::forget('global_notices_active');
        event(new NoticePublished($notice));

        return redirect()->route('admin.notices.index')->with('success', 'Notice created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $notice = Notice::findOrFail($id);

        return view('features.notices.admin.edit', compact('notice'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,danger',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $notice = Notice::findOrFail($id);
        $notice->update($request->only([
            'title',
            'message',
            'type',
            'expires_at',
            'is_active',
        ]));

        Cache::forget('global_notices_active');
        event(new NoticePublished($notice));

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy(string $id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();
        Cache::forget('global_notices_active');

        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');
    }
}
