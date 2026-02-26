<?php

namespace App\Features\Notifications\Http\Controllers;

use App\Features\Notices\Models\Notice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();

        // Get active notices that are not expired
        $notices = Notice::active()
            ->orderByDesc('created_at')
            ->paginate(10);

        // Get IDs of notices already read by this user
        $readNoticeIds = $user ? $user->readNotices()->pluck('notice_id')->toArray() : [];

        // Add read status to each notice
        $notices->getCollection()->transform(function ($notice) use ($readNoticeIds) {
            $notice->is_read = in_array($notice->id, $readNoticeIds);

            return $notice;
        });

        $name = (string) $request->route()?->getName();
        $page = str_starts_with($name, 'hr.')
            ? 'HR/Notifications/Index'
            : (str_starts_with($name, 'employee.') ? 'Employee/Notifications/Index' : 'Notifications/Index');

        return Inertia::render($page, [
            'notices' => $notices,
        ]);
    }

    /**
     * Mark a notice as read for the current user.
     */
    public function markAsRead(Request $request, $noticeId)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notice = Notice::findOrFail($noticeId);

        // Check if already read
        if (! $user->readNotices()->where('notice_id', $noticeId)->exists()) {
            $user->readNotices()->attach($noticeId, ['read_at' => now()]);
        }

        return response()->json(['success' => true, 'message' => 'Notice marked as read']);
    }

    /**
     * Get unread notice count for the current user.
     */
    public function unreadCount(): Response
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['count' => 0]);
        }

        $readIds = $user->readNotices()->pluck('notice_id')->toArray();

        $count = Notice::active()
            ->whereNotIn('id', $readIds)
            ->count();

        return response()->json(['count' => $count]);
    }
}
