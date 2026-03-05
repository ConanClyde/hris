<?php

namespace App\Events;

use App\Features\Posts\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PostCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Post $post;

    public array $postData;

    public ?int $actorUserId;

    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->actorUserId = Auth::id();

        $this->post->loadMissing('author');

        $this->postData = [
            'id' => $this->post->id,
            'title' => $this->post->title,
            'body' => $this->post->body,
            'role_scope' => $this->post->role_scope,
            'is_pinned' => (bool) $this->post->is_pinned,
            'is_published' => (bool) $this->post->is_published,
            'comments_count' => (int) ($this->post->comments_count ?? 0),
            'reactions_count' => (int) ($this->post->reactions_count ?? 0),
            'author' => $this->post->author?->only(['id', 'name', 'first_name', 'last_name']),
            'created_at' => $this->post->created_at,
        ];
    }

    public function broadcastOn(): array
    {
        $scope = (string) ($this->post->role_scope ?? 'all');

        if ($scope === 'hr') {
            return [new PrivateChannel('posts.hr')];
        }

        if ($scope === 'employee') {
            return [new PrivateChannel('posts.employee')];
        }

        return [new PrivateChannel('posts.all')];
    }

    public function broadcastAs(): string
    {
        return 'post.created';
    }

    public function broadcastWith(): array
    {
        return [
            'post' => $this->postData,
            'actor_user_id' => $this->actorUserId,
        ];
    }
}
