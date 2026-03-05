<?php

namespace App\Features\Posts\Http\Controllers\HR;

use App\Events\PostCommentCreated;
use App\Events\PostCreated;
use App\Events\PostReactionUpdated;
use App\Features\Posts\Models\Post;
use App\Features\Posts\Models\PostComment;
use App\Features\Posts\Models\PostReaction;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        if ($request->expectsJson() && $request->query('only') === 'latest_post') {
            $latest = Post::query()
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->orderByDesc('created_at')
                ->with('author')
                ->withCount(['comments', 'reactions'])
                ->first();

            $latestPost = null;
            if ($latest) {
                $latestPost = [
                    'id' => $latest->id,
                    'title' => $latest->title,
                    'body' => $latest->body,
                    'image_url' => $latest->image_path ? asset('storage/'.$latest->image_path) : null,
                    'role_scope' => $latest->role_scope,
                    'is_published' => $latest->is_published,
                    'comments_count' => $latest->comments_count,
                    'reactions_count' => $latest->reactions_count,
                    'author' => $latest->author?->only(['id', 'name', 'first_name', 'last_name']),
                    'created_at' => $latest->created_at,
                ];
            }

            return Inertia::render('HR/Posts/Index', [
                'latestPost' => $latestPost,
            ]);
        }

        $query = Post::query()
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at');

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
            ->through(function (Post $post): array {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'image_url' => $post->image_path ? asset('storage/'.$post->image_path) : null,
                    'role_scope' => $post->role_scope,
                    'is_published' => $post->is_published,
                    'comments_count' => $post->comments_count,
                    'reactions_count' => $post->reactions_count,
                    'author' => $post->author?->only(['id', 'name', 'first_name', 'last_name']),
                    'created_at' => $post->created_at,
                ];
            });

        return Inertia::render('HR/Posts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'body' => 'required|string',
            'role_scope' => 'nullable|string|in:all,hr,employee',
            'image' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp|max:5120',
            'expires_at' => 'nullable|date',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $expiresAt = array_key_exists('expires_at', $validated)
            ? ($validated['expires_at']
                ? now()->parse($validated['expires_at'])->endOfDay()
                : null)
            : now()->addDay();

        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image_path' => $imagePath,
            'expires_at' => $expiresAt,
            'role_scope' => $validated['role_scope'] ?? 'employee',
            'is_published' => true,
            'created_by' => $user?->id,
        ]);

        broadcast(new PostCreated($post))->toOthers();

        $this->notifyUsersForPost($post, $user);

        return redirect()->route('hr.posts.index')->with('success', 'Post created.');
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'body' => 'required|string',
            'role_scope' => 'nullable|string|in:all,hr,employee',
            'is_pinned' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
        ]);

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'role_scope' => $validated['role_scope'] ?? $post->role_scope,
            'is_pinned' => array_key_exists('is_pinned', $validated) ? (bool) $validated['is_pinned'] : $post->is_pinned,
            'is_published' => array_key_exists('is_published', $validated) ? (bool) $validated['is_published'] : $post->is_published,
        ]);

        return redirect()->route('hr.posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('hr.posts.index')->with('success', 'Post deleted.');
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

    protected function notifyUsersForPost(Post $post, ?User $actor): void
    {
        $roleScope = $post->role_scope;

        $query = User::query()->where('is_active', true);

        if ($roleScope === 'hr') {
            $query->where('role', UserRole::Hr->value);
        } elseif ($roleScope === 'employee') {
            $query->where('role', UserRole::Employee->value);
        }

        if ($actor) {
            $query->where('id', '!=', $actor->id);
        }

        $actorPayload = $actor
            ? [
                'id' => $actor->id,
                'name' => $actor->full_name,
                'avatar' => $actor->avatar,
            ]
            : null;

        $title = 'New Announcement';
        $message = $post->title;

        $query->each(function (User $recipient) use ($post, $title, $message, $actorPayload): void {
            $recipient->notify(new SystemNotification(
                type: 'info',
                title: $title,
                message: $message,
                data: [
                    'redirect_url' => '/employee/posts',
                    'post_id' => $post->id,
                ],
                actor: $actorPayload,
            ));
        });
    }
}
