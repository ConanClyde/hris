<?php

namespace App\Features\Calendar\Events;

use App\Features\Calendar\Models\CustomHoliday;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class CustomHolidayCreated implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(
        public CustomHoliday $holiday,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('calendar.holidays'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'CustomHolidayCreated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->holiday->id,
            'title' => $this->holiday->title,
            'date' => $this->holiday->date->toDateString(),
            'category' => $this->holiday->category,
            'is_recurring' => (bool) $this->holiday->is_recurring,
        ];
    }
}
