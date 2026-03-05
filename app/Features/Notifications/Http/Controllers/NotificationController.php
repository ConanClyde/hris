<?php

namespace App\Features\Notifications\Http\Controllers;

use App\Events\NotificationsUnreadCountUpdated;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * @return JsonResponse|Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        $query = $user->notifications()->latest();

        $filter = (string) $request->query('filter', '');
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(15)->through(function ($notification): array {
            $data = is_array($notification->data) ? $notification->data : [];

            $actorName = data_get($data, 'actor.name')
                ?? data_get($data, 'user_name')
                ?? 'System';

            $actorAvatar = data_get($data, 'actor.avatar')
                ?? data_get($data, 'user_avatar');

            return [
                'id' => (string) $notification->id,
                'title' => (string) (data_get($data, 'title') ?? 'Notification'),
                'body' => (string) (data_get($data, 'message') ?? data_get($data, 'body') ?? ''),
                'type' => (string) (data_get($data, 'type') ?? 'info'),
                'icon' => data_get($data, 'icon'),
                'data' => (array) (data_get($data, 'data') ?? $data),
                'is_read' => (bool) $notification->read_at,
                'created_at' => $notification->created_at?->toISOString(),
                'user_name' => (string) $actorName,
                'user_avatar' => is_string($actorAvatar) && $actorAvatar !== ''
                    ? (Str::startsWith($actorAvatar, ['http://', 'https://']) ? $actorAvatar : asset('storage/'.$actorAvatar))
                    : null,
            ];
        });

        if ($request->expectsJson() && $request->query('only') === 'notifications') {
            return response()->json([
                'props' => [
                    'notifications' => $notifications,
                ],
            ]);
        }

        $name = (string) $request->route()?->getName();
        if (str_starts_with($name, 'admin.')) {
            $page = 'Admin/Notifications/Index';
        } elseif (str_starts_with($name, 'hr.')) {
            $page = 'HR/Notifications/Index';
        } elseif (str_starts_with($name, 'employee.')) {
            $page = 'Employee/Notifications/Index';
        } else {
            $page = 'Notifications/Index';
        }

        return Inertia::render($page, [
            'notifications' => $notifications,
            'filter' => $filter,
        ]);
    }

    /**
     * Mark a notification as read for the current user.
     */
    public function markAsRead(Request $request, string $noticeId)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = $user->notifications()->whereKey($noticeId)->firstOrFail();

        $notification->markAsRead();
        $this->broadcastUnreadCount($user);

        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    public function markAllRead(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->unreadNotifications()->update(['read_at' => now()]);
        $this->broadcastUnreadCount($user);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, string $noticeId)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = $user->notifications()->whereKey($noticeId)->firstOrFail();
        $notification->delete();

        $this->broadcastUnreadCount($user);

        return response()->json(['success' => true]);
    }

    public function destroyAll(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->notifications()->delete();
        $this->broadcastUnreadCount($user);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notification count for the current user.
     */
    public function unreadCount()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['count' => 0]);
        }

        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    private function broadcastUnreadCount(User $user): void
    {
        event(new NotificationsUnreadCountUpdated($user->id, $user->unreadNotifications()->count()));
    }
}
