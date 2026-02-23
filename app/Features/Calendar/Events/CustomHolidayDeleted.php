<?php

namespace App\Features\Calendar\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CustomHolidayDeleted implements ShouldBroadcastNow
{
    public function __construct(
        public int $holidayId,
    ) {
    }

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
            'id' => $this->holidayId,
        ];
    }
}
