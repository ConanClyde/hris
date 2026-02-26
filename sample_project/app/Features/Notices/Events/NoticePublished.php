<?php

namespace App\Features\Notices\Events;

use App\Features\Notices\Models\Notice;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class NoticePublished implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(
        public Notice $notice
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('role.admin'),
            new PrivateChannel('role.hr'),
            new PrivateChannel('role.employee'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NoticePublished';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notice->id,
            'title' => $this->notice->title,
            'type' => $this->notice->type,
            'message' => $this->notice->message,
            'expires_at' => optional($this->notice->expires_at)->toDateString(),
        ];
    }
}
