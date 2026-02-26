<?php

namespace App\Events;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveCancelled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public LeaveApplication $leave;

    public array $leaveData;

    /**
     * Create a new event instance.
     */
    public function __construct(LeaveApplication $leave)
    {
        $this->leave = $leave;
        $this->leaveData = [
            'id' => $leave->id,
            'employee_id' => $leave->employee_id,
            'employee_name' => $leave->employee_name,
            'leave_type' => $leave->type,
            'start_date' => $leave->date_from,
            'end_date' => $leave->date_to,
            'status' => $leave->status,
            'cancelled_at' => now(),
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
            new PrivateChannel('App.Models.User.'.$this->leave->employee_id),
            new PrivateChannel('hr.dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'leave.cancelled';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'leave' => $this->leaveData,
            'message' => 'Your leave application has been cancelled',
            'type' => 'warning',
        ];
    }
}
