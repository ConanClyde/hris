<?php

namespace App\Features\Pds\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PdsStatusUpdated implements ShouldBroadcastNow
{
    public function __construct(
        public int $id,
        public int $employeeId,
        public ?string $employeeName,
        public string $status,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('role.hr'),
            new PrivateChannel('role.employee'),
            new PrivateChannel('pds.management'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PdsStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'employee_name' => $this->employeeName,
            'status' => $this->status,
        ];
    }
}
