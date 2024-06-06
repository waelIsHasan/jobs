<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $message;
    public $chatId;

    public function __construct($message , $chatId)
    {
        $this->message = $message;
        
        $this->chatId = $chatId;
    }

  
    public function broadcastOn()
    {
        return new Channel('chat'.''.$this->chatId);
    }

    public function broadcastAs()
    {
       return 'myBroadcast';
    }


}
