<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NoticeDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $noticeId;

    public string $noticeTitle;

    /**
     * Create a new event instance.
     */
    public function __construct(int $noticeId, string $noticeTitle = '')
    {
        $this->noticeId = $noticeId;
        $this->noticeTitle = $noticeTitle;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('employees'),
            new PrivateChannel('hr.dashboard'),
            new PrivateChannel('admin.dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notice.deleted';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->noticeId,
            'message' => $this->noticeTitle ? "Notice removed: {$this->noticeTitle}" : 'Notice removed',
            'type' => 'warning',
        ];
    }
}
