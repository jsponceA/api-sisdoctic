<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExampleNotification implements  ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $message;
    private string | int  $userId;
    /**
     * Create a new event instance.
     *
     * @param string $message
     */
    //parametros
    public function __construct(string $message, string | int $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        //nombre del canal privato necestia autorizacion del token
        return [
            new PrivateChannel("example.notification.private.{$this->userId}"),
        ];
    }

    //nombre del evento
    public function  broadcastAs(): string
    {
        return "example.notification.event";
    }

    //datos que se van a enviar al cliente
    public function broadcastWith(): array
    {
        return [
            "message" => $this->message,
            "user_id" => $this->userId,
        ];
    }
}
