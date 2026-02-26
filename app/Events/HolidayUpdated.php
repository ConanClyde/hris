<?php

namespace App\Events;

use App\Models\CustomHoliday;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HolidayUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CustomHoliday $holiday;

    public array $holidayData;

    /**
     * Create a new event instance.
     */
    public function __construct(CustomHoliday $holiday)
    {
        $this->holiday = $holiday;
        $this->holidayData = [
            'id' => $holiday->id,
            'title' => $holiday->title,
            'date' => $holiday->date,
            'category' => $holiday->category,
            'description' => $holiday->description,
            'is_recurring' => $holiday->is_recurring,
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
        return 'holiday.updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'holiday' => $this->holidayData,
            'message' => "Holiday updated: {$this->holiday->title}",
            'type' => 'warning',
        ];
    }
}
