<?php

namespace App\Events;

use App\Models\Notifikasi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(Notifikasi $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('user.' . $this->notification->user_id);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notification.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'user_id' => $this->notification->user_id,
            'judul' => $this->notification->judul,
            'pesan' => $this->notification->pesan,
            'tipe' => $this->notification->tipe,
            'link' => $this->notification->link,
            'dibaca' => $this->notification->dibaca,
            'created_at' => $this->notification->created_at->diffForHumans(),
        ];
    }
}
