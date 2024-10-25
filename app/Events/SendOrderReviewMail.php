<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendOrderReviewMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    Public $data;
    Public $order;
    Public $dArr;
    Public $owner;
    Public $store;
    Public $order_id;
    public function __construct($data ,$order ,$dArr, $owner, $store, $order_id)
    {
        $this->data = $data;
        $this->order = $order;
        $this->dArr = $dArr;
        $this->owner = $owner;
        $this->store = $store;
        $this->order_id = $order_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
