<?php

namespace App\Events;

use App\Features\Notices\Models\Notice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NoticeCreated implements ShouldBroadcastNow
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
            'created_at' => $notice->created_at,
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
            // Broadcast to all employees
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
        return 'notice.created';
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
            'message' => "New notice: {$this->notice->title}",
            'type' => $this->notice->type === 'danger' ? 'error' : ($this->notice->type === 'warning' ? 'warning' : 'info'),
        ];
    }
}
