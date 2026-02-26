<?php

namespace App\Http\Controllers\Api;

use App\Features\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MeController extends Controller
{
    /**
     * Return current user profile for sidebar and real-time sync.
     */
    public function show()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $user->id;
        $cacheKey = 'api_me_'.(int) $userId;

        $payload = Cache::remember($cacheKey, 10, function () use ($user) {
            $emp = $user->employee;
            $displayName = null;
            if ($emp && trim($emp->first_name.' '.$emp->last_name)) {
                $displayName = trim($emp->first_name.' '.$emp->last_name);
            }
            if (! $displayName) {
                $displayName = $user->display_name ?: ($user->name ?: null);
            }
            if (! $displayName) {
                $displayName = $user->email ?: 'User';
            }

            $avatarPath = session('avatar');
            $initial = strtoupper(mb_substr($displayName, 0, 1)) ?: 'U';

            return [
                'id' => $user->id,
                'display_name' => $displayName,
                'email' => $user->email ?: '',
                'role' => $user->role,
                'initial' => $initial,
                'avatar_url' => $avatarPath ? asset('storage/'.$avatarPath) : null,
            ];
        });

        return response()->json($payload);
    }
}
