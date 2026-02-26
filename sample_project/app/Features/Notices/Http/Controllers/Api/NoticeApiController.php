<?php

namespace App\Features\Notices\Http\Controllers\Api;

use App\Features\Notices\Models\Notice;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeApiController extends Controller
{
    /**
     * GET /api/notices/active (original endpoint, kept for compatibility)
     */
    public function active(): JsonResponse
    {
        $notices = Notice::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest()
            ->get();

        return response()->json($notices);
    }

    /**
     * GET /api/notifications
     * Returns recent active notices with is_read flag for the current user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $limit = min((int) $request->query('limit', 10), 50);

        $notices = Notice::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest()
            ->limit($limit)
            ->get();

        $readIds = DB::table('notice_reads')
            ->where('user_id', $user->id)
            ->whereIn('notice_id', $notices->pluck('id'))
            ->pluck('notice_id')
            ->toArray();

        $unreadCount = Notice::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->whereNotIn('id', DB::table('notice_reads')
                ->where('user_id', $user->id)
                ->pluck('notice_id'))
            ->count();

        $items = $notices->map(function ($notice) use ($readIds) {
            return [
                'id' => $notice->id,
                'title' => $notice->title,
                'message' => $notice->message,
                'type' => $notice->type,
                'is_read' => in_array($notice->id, $readIds),
                'created_at' => $notice->created_at->diffForHumans(),
                'raw_date' => $notice->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'items' => $items,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * POST /api/notifications/mark-read
     * Mark all active notices as read for the current user.
     */
    public function markAllRead(): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $activeNoticeIds = Notice::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->pluck('id');

        $alreadyRead = DB::table('notice_reads')
            ->where('user_id', $user->id)
            ->whereIn('notice_id', $activeNoticeIds)
            ->pluck('notice_id')
            ->toArray();

        $toInsert = $activeNoticeIds
            ->diff($alreadyRead)
            ->map(fn ($id) => [
                'user_id' => $user->id,
                'notice_id' => $id,
                'read_at' => now(),
            ])
            ->values()
            ->toArray();

        if (! empty($toInsert)) {
            DB::table('notice_reads')->insert($toInsert);
        }

        return response()->json(['success' => true, 'marked' => count($toInsert)]);
    }

    /**
     * POST /api/notifications/{id}/read
     * Mark a single notice as read for the current user.
     */
    public function markRead(int $id): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        DB::table('notice_reads')->insertOrIgnore([
            'user_id' => $user->id,
            'notice_id' => $id,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
