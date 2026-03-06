<?php

namespace App\Events;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public LeaveApplication $leave;

    public array $leaveData;

    public string $approverName;

    /**
     * Create a new event instance.
     */
    public function __construct(LeaveApplication $leave, string $approverName = '')
    {
        $this->leave = $leave;
        $this->approverName = $approverName;
        $this->leaveData = [
            'id' => $leave->id,
            'employee_id' => $leave->employee_id,
            'employee_name' => $leave->employee_name,
            'leave_type' => $leave->type,
            'start_date' => $leave->date_from,
            'end_date' => $leave->date_to,
            'status' => $leave->status,
            'approver_name' => $approverName,
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
            // Notify HR dashboard
            new PrivateChannel('hr.dashboard'),
        ];

        $userId = $this->leave->employee?->user_id;

        if (is_int($userId)) {
            // Notify the employee whose leave was approved (per-user channel)
            $channels[] = new PrivateChannel('App.Models.User.'.$userId);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'leave.approved';
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
            'message' => "Your leave application has been approved by {$this->approverName}",
            'type' => 'success',
        ];
    }
}
