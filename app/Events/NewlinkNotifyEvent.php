<?php

namespace App\Events;

use App\Models\Links;
use Illuminate\Queue\SerializesModels;

class NewlinkNotifyEvent
{
    use SerializesModels;

    public Links $links;

    /**
     * Creates the new link notification event.
     */
    public function __construct(Links $links)
    {
        $this->links = $links;
    }

    /**
     * Returns the event broadcast channels.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
