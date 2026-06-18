<?php

namespace App\Events;

use App\Models\Links;
use Illuminate\Queue\SerializesModels;

class NewlinkNotifyEvent
{
    use SerializesModels;

    public Links $links;

    /**
     * Создает событие уведомления о новой ссылке.
     */
    public function __construct(Links $links)
    {
        $this->links = $links;
    }

    /**
     * Возвращает каналы трансляции события.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
