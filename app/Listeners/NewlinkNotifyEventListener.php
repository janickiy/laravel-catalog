<?php

namespace App\Listeners;

use App\Events\NewlinkNotifyEvent;
use App\Helpers\SettingsHelpers;
use App\Mail\NewlinkNotify;
use Illuminate\Support\Facades\Mail;

class NewlinkNotifyEventListener
{
    /**
     * Creates the new link event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Sends the administration an email about a new link.
     */
    public function handle(NewlinkNotifyEvent $event): void
    {
        $email = SettingsHelpers::notificationEmail();

        if ($email === null) {
            return;
        }

        Mail::to($email)->send(new NewlinkNotify($event->links));
    }
}
