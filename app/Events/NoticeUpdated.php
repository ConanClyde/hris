<?php

namespace App\Events;

use App\Features\Notices\Models\Notice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NoticeUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notice $notice;

    public array $noticeData;

    /**
     * Create a new event instance.
     */
    public function __construct(Notice $notice)
    {
        $this->notice = $notice;
        $this->noticeData = [
            'id' => $notice->id,
            'title' => $notice->title,
            'message' => $notice->message,
            'type' => $notice->type,
            'is_active' => $notice->is_active,
            'expires_at' => $notice->expires_at?->toDateString(),
            'updated_at' => $notice->updated_at,
        ];
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
        return 'notice.updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'notice' => $this->noticeData,
            'message' => "Notice updated: {$this->notice->title}",
            'type' => 'warning',
        ];
    }
}
