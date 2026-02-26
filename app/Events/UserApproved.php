<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserApproved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public array $userData;

    public string $approverName;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $approverName = '')
    {
        $this->user = $user;
        $this->approverName = $approverName;
        $this->userData = [
            'id' => $user->id,
            'name' => $user->full_name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
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
            new PrivateChannel('App.Models.User.'.$this->user->id),
            new PrivateChannel('admin.dashboard'),
            new PrivateChannel('hr.dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.approved';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'user' => $this->userData,
            'message' => "Your account has been approved by {$this->approverName}",
            'type' => 'success',
        ];
    }
}
