<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use stdClass;

class FeedbackMailEvent
{
    use SerializesModels;

    public stdClass $data;

    /**
     * Создает событие отправки письма обратной связи.
     */
    public function __construct(stdClass $data)
    {
        $this->data = $data;
    }

    /**
     * Возвращает каналы трансляции события.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
