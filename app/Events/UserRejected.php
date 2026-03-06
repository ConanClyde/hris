<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRejected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public array $userData;

    public string $approverName;

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

    public function broadcastAs(): string
    {
        return 'user.rejected';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'user' => $this->userData,
            'message' => "Your account registration has been rejected by {$this->approverName}",
            'type' => 'error',
        ];
    }
}
