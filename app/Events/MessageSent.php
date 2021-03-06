<?php
namespace App\Events;

use App\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;
    public $message;
    public $sender;
    private $chat_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->sender = $message->user->name;
        $this->chat_id = $message->chat_id;
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