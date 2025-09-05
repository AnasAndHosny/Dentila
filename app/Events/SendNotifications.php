<?php

namespace App\Events;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendNotifications implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $data, public User $user)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('User.' . $this->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification';
    }

    public function broadcastWith(): array
    {
        return [
            'title_ar' => $this->data['title_ar'],
            'title_en' => $this->data['title_en'],
            'body_ar' => $this->data['body_ar'],
            'body_en' => $this->data['body_en'],
        ];
    }
}
