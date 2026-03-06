<?php

namespace App\Events;

use App\Features\Training\Models\Training;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrainingAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Training $training;

    public array $trainingData;

    public int $employeeId;

    public int $assignedTo;

    /**
     * Create a new event instance.
     */
    public function __construct(Training $training, int $assignedTo)
    {
        $this->training = $training;
        $this->assignedTo = $assignedTo;
        $this->employeeId = $training->employee_id;
        $this->trainingData = [
            'id' => $training->id,
            'employee_id' => $training->employee_id,
            'employee_fk' => $training->employee_fk,
            'employee_name' => $training->employee_name,
            'title' => $training->title,
            'date_from' => $training->date_from,
            'date_to' => $training->date_to,
            'status' => $training->status,
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
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'training.assigned';
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
            'message' => "You have been assigned to training: {$this->training->title}",
            'type' => 'info',
        ];
    }
}
