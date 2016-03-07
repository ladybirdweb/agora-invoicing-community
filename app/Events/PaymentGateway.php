<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentGateway extends Event
{
    use SerializesModels;
    
    public $para;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($para)
    {
        $this->para = $para;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
