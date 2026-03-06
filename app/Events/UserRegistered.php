<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public array $userData;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->userData = [
            'id' => $user->id,
            'name' => $user->full_name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'created_at' => $user->created_at,
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
            new PrivateChannel('admin.dashboard'),
            new PrivateChannel('hr.dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.registered';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $userData = [
            'id' => $this->user->id,
            'name' => $this->user->full_name,
            'first_name' => $this->user->first_name,
            'middle_name' => $this->user->middle_name,
            'last_name' => $this->user->last_name,
            'name_extension' => $this->user->name_extension,
            'email' => $this->user->email,
            'username' => $this->user->username,
            'role' => $this->user->role,
            'status' => $this->user->status,
            'is_active' => $this->user->is_active,
            'created_at' => $this->user->created_at,
        ];

        // Include employee data if available
        if ($this->user->employee) {
            $userData['employee'] = [
                'position' => $this->user->employee->position,
                'classification' => $this->user->employee->classification,
                'date_hired' => $this->user->employee->date_hired,
                'division' => $this->user->employee->division,
                'subdivision' => $this->user->employee->subdivision,
                'section' => $this->user->employee->section,
                'division_id' => $this->user->employee->division_id,
                'subdivision_id' => $this->user->employee->subdivision_id,
                'section_id' => $this->user->employee->section_id,
            ];
        }

        return [
            'user' => $userData,
            'message' => "New user registered: {$this->user->full_name}",
            'type' => 'info',
        ];
    }
}
