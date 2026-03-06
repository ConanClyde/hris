<?php

namespace App\Events;

use App\Features\Posts\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostReactionUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Post $post,
        public int $reactionsCount,
        public ?int $actorUserId = null,
        public ?string $actorReaction = null,
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
        return 'post.reaction.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'post_id' => $this->post->id,
            'reactions_count' => $this->reactionsCount,
            'actor_user_id' => $this->actorUserId,
            'actor_reaction' => $this->actorReaction,
        ];
    }
}
