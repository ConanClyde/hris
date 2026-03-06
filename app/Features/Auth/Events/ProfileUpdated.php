<?php

namespace App\Features\Auth\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProfileUpdated implements ShouldBroadcast
{
    public function __construct(
        public int $userId,
        public array $profile
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.'.(string) $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ProfileUpdated';
    }

    public function broadcastWith(): array
    {
        return $this->profile;
    }
}
