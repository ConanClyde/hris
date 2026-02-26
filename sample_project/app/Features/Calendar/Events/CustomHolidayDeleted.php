<?php

namespace App\Features\Calendar\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class CustomHolidayDeleted implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(
        public int $id,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('calendar.holidays'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'CustomHolidayDeleted';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
