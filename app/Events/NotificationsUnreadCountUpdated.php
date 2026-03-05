<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationsUnreadCountUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;

    public int $unreadCount;

    public function __construct(int $userId, int $unreadCount)
    {
        $this->userId = $userId;
        $this->unreadCount = $unreadCount;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notifications.unread.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'count' => $this->unreadCount,
        ];
    }
}
