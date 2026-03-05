<?php

namespace App\Events;

use App\Features\Posts\Models\Post;
use App\Features\Posts\Models\PostComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Post $post,
        public PostComment $comment,
        public int $commentsCount,
    ) {}

    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('posts.all')];

        if ($this->post->role_scope === 'hr') {
            $channels[] = new PrivateChannel('posts.hr');
        } elseif ($this->post->role_scope === 'employee') {
            $channels[] = new PrivateChannel('posts.employee');
        } else {
            $channels[] = new PrivateChannel('posts.hr');
            $channels[] = new PrivateChannel('posts.employee');
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'post.comment.created';
    }

    public function broadcastWith(): array
    {
        return [
            'post_id' => $this->post->id,
            'comments_count' => $this->commentsCount,
            'comment' => [
                'id' => $this->comment->id,
                'body' => $this->comment->body,
                'user_id' => $this->comment->user_id,
                'created_at' => $this->comment->created_at,
            ],
        ];
    }
}
