<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class CommentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $data;
    private $user;
    private $idProduct;

    public function __construct($data, User $user,$idProduct)
    {
        //
        $this->data = $data;
        $this->user = $user;
        $this->idProduct = $idProduct;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('product.' . $this->idProduct);
    }

    public function broadcastAs()
    {
        return 'comment.created';
    }

    public function broadcastWith()
    {
        return [
            'name' => $this->user->name,
            'data' => $this->data
        ];
    }
}
