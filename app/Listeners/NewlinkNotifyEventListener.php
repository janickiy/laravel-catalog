<?php

namespace App\Listeners;

use App\Events\NewlinkNotifyEvent;
use App\Helpers\SettingsHelpers;
use App\Mail\NewlinkNotify;
use Illuminate\Support\Facades\Mail;

class NewlinkNotifyEventListener
{
    /**
     * Создает обработчик события новой ссылки.
     */
    public function __construct()
    {
        //
    }

    /**
     * Отправляет письмо администрации о новой ссылке.
     */
    public function handle(NewlinkNotifyEvent $event): void
    {
        Mail::to(SettingsHelpers::getSetting('EMAIL'))->send(new NewlinkNotify($event->links));
    }
}
