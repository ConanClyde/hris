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
