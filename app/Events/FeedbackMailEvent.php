<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use stdClass;

class FeedbackMailEvent
{
    use SerializesModels;

    public stdClass $data;

    /**
     * Creates the feedback mail event.
     */
    public function __construct(stdClass $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the event broadcast channels.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
