<?php

namespace App\Events;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveSubmitted implements ShouldBroadcastNow
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
            'reason' => $leave->reason,
            'status' => $leave->status,
            'created_at' => $leave->created_at,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            // Notify HR team
            new PrivateChannel('hr.dashboard'),
        ];

        $userId = $this->leave->employee?->user_id;

        if (is_int($userId)) {
            // Notify the employee who submitted (per-user channel)
            $channels[] = new PrivateChannel('App.Models.User.'.$userId);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'leave.submitted';
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
            'message' => 'New leave application submitted',
            'type' => 'info',
        ];
    }
}
