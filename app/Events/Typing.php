<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Typing implements ShouldBroadcast
{
    use SerializesModels;
    public $user;
    private $chat_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $chat_id)
    {
        $this->user = $user;
        $this->chat_id = $chat_id;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        $channel = "chat-channel.".$this->chat_id;
        return [$channel];
    }
}
