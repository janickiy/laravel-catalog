<?php

namespace App\Events;

use App\Models\Links;
use Illuminate\Queue\SerializesModels;

class NewlinkNotifyEvent
{
    use SerializesModels;

    public Links $links;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Links $links)
    {
        $this->links = $links;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
