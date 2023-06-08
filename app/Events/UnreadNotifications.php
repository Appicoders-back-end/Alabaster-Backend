<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnreadNotifications implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $unreadNotifications = 0;
    public $receiver_id = null;

    public function __construct($unreadNotifications, $receiverId)
    {
        $this->unreadNotifications = $unreadNotifications;
        $this->receiver_id = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('unread_notifications');
    }

    public function broadcastWith()
    {
        return [
            'unread_notifications' => $this->unreadNotifications,
            'receiver_id' => $this->receiver_id
        ];
    }
}
