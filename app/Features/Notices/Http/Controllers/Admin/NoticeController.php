<?php

namespace App\Features\Notices\Http\Controllers\Admin;

use App\Events\NoticeCreated;
use App\Events\NoticeDeleted;
use App\Events\NoticeUpdated;
use App\Features\Notices\Models\Notice;
use App\Http\Controllers\Controller;
use App\Mail\NoticeCreatedMail;
use App\Mail\NoticeDeletedMail;
use App\Mail\NoticeUpdatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();

        // Debug: Log the query
        \Log::info('Notices index query', [
            'user_id' => Auth::id(),
            'user_role' => Auth::user()?->role,
        ]);

        $notices = Notice::orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery);

        \Log::info('Notices count', ['total' => $notices->total(), 'count' => $notices->count()]);

        return Inertia::render('Admin/Notices/Index', ['notices' => $notices]);
    }

    public function create(): Response
    {
        return redirect()->route('admin.notices.index');
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

        $notice = Notice::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'] ?? 'info',
            'is_active' => $validated['is_active'] ?? true,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        // Broadcast notice created event
        \Log::info('Broadcasting NoticeCreated', ['notice_id' => $notice->id, 'publisher' => Auth::id()]);
        broadcast(new NoticeCreated($notice))->toOthers();

        // Realtime + badge behavior for notices is now handled via the new SystemNotification flow.

        // Email all active users about the new notice
        $recipients = User::query()
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($recipients !== []) {
            $publishedBy = Auth::user()?->full_name ?? Auth::user()?->name ?? 'Admin';
            Mail::to($recipients)->queue(new NoticeCreatedMail($notice, $publishedBy));
        }

        return redirect()->route('admin.notices.index')->with('success', 'Notice created.');
    }

    public function edit($id)
    {
        return redirect()->route('admin.notices.index');
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

        // Broadcast notice updated event
        broadcast(new NoticeUpdated($notice))->toOthers();

        // Email all active users about the updated notice
        $recipients = User::query()
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($recipients !== []) {
            $updatedBy = Auth::user()?->full_name ?? Auth::user()?->name ?? 'Admin';
            Mail::to($recipients)->queue(new NoticeUpdatedMail($notice, $updatedBy));
        }

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated.');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        $title = $notice->title;
        $category = $notice->type ?? 'info';
        $notice->delete();

        // Broadcast notice deleted event
        broadcast(new NoticeDeleted($id, $title))->toOthers();

        // Email all active users about the removed notice
        $recipients = User::query()
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($recipients !== []) {
            $removedBy = Auth::user()?->full_name ?? Auth::user()?->name ?? 'Admin';
            $removedDate = now()->toDayDateTimeString();
            Mail::to($recipients)->queue(new NoticeDeletedMail($title, $category, $removedDate, $removedBy));
        }

        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted.');
    }
}
