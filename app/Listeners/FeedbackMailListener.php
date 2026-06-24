<?php

namespace App\Listeners;

use App\Events\FeedbackMailEvent;
use App\Helpers\SettingsHelpers;
use App\Mail\FeedbackMailer;
use Illuminate\Support\Facades\Mail;

class FeedbackMailListener
{
    /**
     * Creates the feedback event listener.
     */
    public function __construct()
    {
        // .....
    }

    /**
     * Sends the administration an email for the feedback event.
     */
    public function handle(FeedbackMailEvent $event): void
    {
        Mail::to(SettingsHelpers::getSetting('EMAIL'))->send(new FeedbackMailer($event->data));
    }
}
