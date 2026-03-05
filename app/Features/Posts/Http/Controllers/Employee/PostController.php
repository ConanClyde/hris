<?php

namespace App\Features\Posts\Http\Controllers\Employee;

use App\Events\PostCommentCreated;
use App\Events\PostReactionUpdated;
use App\Features\Posts\Models\Post;
use App\Features\Posts\Models\PostComment;
use App\Features\Posts\Models\PostReaction;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user?->getRoleEnum() ?? UserRole::Employee;

        $query = Post::query()
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at');

        if ($role === UserRole::Hr) {
            $query->whereIn('role_scope', ['all', 'hr']);
        } elseif ($role === UserRole::Employee) {
            $query->whereIn('role_scope', ['all', 'employee']);
        }

        if ($search = trim((string) $request->query('search', ''))) {
            $pairs = [];
            $freeTerms = [];

            foreach (preg_split('/\s+/', $search) as $token) {
                $token = trim((string) $token);
                if ($token === '') {
                    continue;
                }

                if (! Str::contains($token, ':')) {
                    $freeTerms[] = $token;

                    continue;
                }

                [$rawKey, $rawValue] = array_pad(explode(':', $token, 2), 2, '');
                $key = strtolower(trim($rawKey));
                $value = trim($rawValue);
                if ($value === '') {
                    continue;
                }

                if ($key === 'user') {
                    $key = 'author';
                }

                if (in_array($key, ['author', 'title', 'body'], true)) {
                    $pairs[] = ['key' => $key, 'value' => $value];
                } else {
                    $freeTerms[] = $token;
                }
            }

            $query->where(function ($q) use ($pairs, $freeTerms) {
                foreach ($pairs as $pair) {
                    $term = $pair['value'];
                    if ($pair['key'] === 'title') {
                        $q->where('title', 'like', '%'.$term.'%');
                    } elseif ($pair['key'] === 'body') {
                        $q->where('body', 'like', '%'.$term.'%');
                    } elseif ($pair['key'] === 'author') {
                        $q->whereHas('author', function ($authorQ) use ($term) {
                            $authorQ->where('first_name', 'like', '%'.$term.'%')
                                ->orWhere('last_name', 'like', '%'.$term.'%')
                                ->orWhere('name', 'like', '%'.$term.'%')
                                ->orWhere('username', 'like', '%'.$term.'%')
                                ->orWhere('email', 'like', '%'.$term.'%');
                        });
                    }
                }

                foreach ($freeTerms as $term) {
                    $q->where(function ($termQ) use ($term) {
                        $termQ->where('title', 'like', '%'.$term.'%')
                            ->orWhere('body', 'like', '%'.$term.'%')
                            ->orWhereHas('author', function ($authorQ) use ($term) {
                                $authorQ->where('first_name', 'like', '%'.$term.'%')
                                    ->orWhere('last_name', 'like', '%'.$term.'%')
                                    ->orWhere('name', 'like', '%'.$term.'%')
                                    ->orWhere('username', 'like', '%'.$term.'%')
                                    ->orWhere('email', 'like', '%'.$term.'%');
                            });
                    });
                }
            });
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();

        $posts = $query
            ->with('author')
            ->withCount(['comments', 'reactions'])
            ->paginate(10)
            ->appends($appendQuery)
            ->through(function (Post $post) use ($user): array {
                $userReaction = null;
                if ($user) {
                    $userReaction = $post->reactions()
                        ->where('user_id', $user->id)
                        ->value('type');
                }

                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'image_url' => $post->image_path ? asset('storage/'.$post->image_path) : null,
                    'role_scope' => $post->role_scope,
                    'created_at' => $post->created_at,
                    'author' => $post->author?->only(['id', 'name', 'first_name', 'last_name']),
                    'comments_count' => $post->comments_count,
                    'reactions_count' => $post->reactions_count,
                    'user_reaction' => $userReaction,
                ];
            });

        return Inertia::render('Employee/Posts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['search']),
        ]);
    }

    public function react(Request $request, Post $post)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        $reaction = PostReaction::updateOrCreate(
            [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ],
            [
                'type' => $validated['type'],
            ]
        );

        $reactionsCount = (int) PostReaction::query()->where('post_id', $post->id)->count();
        broadcast(new PostReactionUpdated($post, $reactionsCount, $user->id, $reaction->type))->toOthers();

        if ($request->expectsJson()) {
            return response()->json([
                'post_id' => $post->id,
                'reactions_count' => $reactionsCount,
                'user_reaction' => $reaction->type,
            ]);
        }

        return back()->with('success', 'Reaction saved.');
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => $validated['body'],
        ]);

        $commentsCount = (int) PostComment::query()->where('post_id', $post->id)->count();
        broadcast(new PostCommentCreated($post, $comment, $commentsCount))->toOthers();

        if ($request->expectsJson()) {
            return response()->json([
                'post_id' => $post->id,
                'comments_count' => $commentsCount,
                'comment' => [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'user_id' => $comment->user_id,
                    'created_at' => $comment->created_at,
                ],
            ]);
        }

        return back()->with('success', 'Comment posted.');
    }
}
