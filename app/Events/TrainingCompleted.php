<?php

namespace App\Events;

use App\Features\Training\Models\Training;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrainingCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Training $training;

    public array $trainingData;

    public int $employeeId;

    public int $employeeUserId;

    /**
     * Create a new event instance.
     */
    public function __construct(Training $training, int $employeeUserId)
    {
        $this->training = $training;
        $this->employeeId = $employeeUserId;
        $this->employeeUserId = $employeeUserId;
        $this->trainingData = [
            'id' => $training->id,
            'employee_id' => $training->employee_id,
            'employee_fk' => $training->employee_fk,
            'employee_name' => $training->employee_name,
            'title' => $training->title,
            'date_from' => $training->date_from,
            'date_to' => $training->date_to,
            'status' => $training->status,
            'completed_at' => now(),
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
            new PrivateChannel('App.Models.User.'.$this->employeeId),
            new PrivateChannel('hr.dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'training.completed';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'training' => $this->trainingData,
            'message' => "You have completed training: {$this->training->title}",
            'type' => 'success',
        ];
    }
}
