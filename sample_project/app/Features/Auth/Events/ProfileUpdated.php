<?php

namespace App\Features\Auth\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(
        public int $userId,
        public array $profile
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.'.$this->userId),
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
