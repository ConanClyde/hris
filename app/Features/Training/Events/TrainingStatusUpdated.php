<?php

namespace App\Features\Training\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TrainingStatusUpdated implements ShouldBroadcast
{
    public function __construct(
        public int $id,
        public string $employeeId,
        public ?string $employeeName,
        public string $status,
        public string $title,
        public string $dateFrom,
        public float $hours,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('role.hr'),
            new PrivateChannel('role.employee'),
            new PrivateChannel('training.management'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TrainingStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'employee_name' => $this->employeeName,
            'status' => $this->status,
            'title' => $this->title,
            'date_from' => $this->dateFrom,
            'hours' => $this->hours,
        ];
    }
}
