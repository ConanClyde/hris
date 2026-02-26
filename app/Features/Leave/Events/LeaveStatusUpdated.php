<?php

namespace App\Features\Leave\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class LeaveStatusUpdated implements ShouldBroadcastNow
{
    public function __construct(
        public int $id,
        public string $employeeId,
        public ?string $employeeName,
        public string $status,
        public string $type,
        public string $dateFrom,
        public float $totalDays,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('role.hr'),
            new PrivateChannel('role.employee'),
            new PrivateChannel('leave.management'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'LeaveStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'employee_name' => $this->employeeName,
            'status' => $this->status,
            'type' => $this->type,
            'date_from' => $this->dateFrom,
            'total_days' => $this->totalDays,
        ];
    }
}
