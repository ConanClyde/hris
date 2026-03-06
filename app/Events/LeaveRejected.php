<?php

namespace App\Events;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveRejected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public LeaveApplication $leave;

    public array $leaveData;

    public string $rejectorName;

    public ?string $rejectionReason;

    /**
     * Create a new event instance.
     */
    public function __construct(LeaveApplication $leave, string $rejectorName = '', ?string $rejectionReason = null)
    {
        $this->leave = $leave;
        $this->rejectorName = $rejectorName;
        $this->rejectionReason = $rejectionReason;
        $this->leaveData = [
            'id' => $leave->id,
            'employee_id' => $leave->employee_id,
            'employee_name' => $leave->employee_name,
            'leave_type' => $leave->type,
            'start_date' => $leave->date_from,
            'end_date' => $leave->date_to,
            'status' => $leave->status,
            'rejection_reason' => $rejectionReason,
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
            new PrivateChannel('hr.dashboard'),
        ];

        $userId = $this->leave->employee?->user_id;

        if (is_int($userId)) {
            $channels[] = new PrivateChannel('App.Models.User.'.$userId);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'leave.rejected';
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
            'message' => "Your leave application has been rejected by {$this->rejectorName}",
            'type' => 'error',
        ];
    }
}
