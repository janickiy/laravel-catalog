<?php

namespace App\Listeners;

use App\Events\FeedbackMailEvent;
use App\Helpers\SettingsHelpers;
use App\Mail\FeedbackMailer;
use Illuminate\Support\Facades\Mail;

class FeedbackMailListener
{
    /**
     * Создает обработчик события обратной связи.
     */
    public function __construct()
    {
        // .....
    }

    /**
     * Отправляет письмо администрации по событию обратной связи.
     */
    public function handle(FeedbackMailEvent $event): void
    {
        Mail::to(SettingsHelpers::getSetting('EMAIL'))->send(new FeedbackMailer($event->data));
    }
}
