<?php

namespace App\Events;

use App\Http\Resources\V1\QueueTurnResource;
use App\Models\User;
use App\Models\QueueTurn;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QueueTurnAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public QueueTurn $queueTurn) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('queue-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue-turn-added';
    }

    public function broadcastWith(): array
    {
        $queueTurn = new QueueTurnResource($this->queueTurn);
        return $queueTurn->toArray(new Request());
    }
}
