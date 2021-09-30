<?php

namespace App\Events;

use stdClass;
use Illuminate\Queue\SerializesModels;

class FeedbackMailEvent {

    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(stdClass $data) {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return [];
    }
}
