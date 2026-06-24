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
        $email = SettingsHelpers::notificationEmail();

        if ($email === null) {
            return;
        }

        Mail::to($email)->send(new FeedbackMailer($event->data));
    }
}
