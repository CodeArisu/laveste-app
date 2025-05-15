<?php

namespace App\Events;

use App\Models\Garments\Garment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GarmentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $garment;
    public $user;
    public function __construct($garment, $user)
    {
        $this->garment = $garment;
        $this->user = $user;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
