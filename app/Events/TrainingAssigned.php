<?php

namespace App\Events;

use App\Features\Training\Models\Training;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrainingAssigned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Training $training;

    public array $trainingData;

    public int $employeeId;

    /**
     * Create a new event instance.
     */
    public function __construct(Training $training, int $employeeId)
    {
        $this->training = $training;
        $this->employeeId = $employeeId;
        $this->trainingData = [
            'id' => $training->id,
            'title' => $training->title,
            'description' => $training->description,
            'start_date' => $training->start_date,
            'end_date' => $training->end_date,
            'location' => $training->location,
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
