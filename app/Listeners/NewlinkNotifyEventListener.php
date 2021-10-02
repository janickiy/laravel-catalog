<?php

namespace App\Listeners;

use App\Events\NewlinkNotifyEvent;
use App\Mail\NewlinkNotify;
use App\Helpers\SettingsHelpers;
use Illuminate\Support\Facades\Mail;

class NewlinkNotifyEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewlinkNotifyEvent  $event
     * @return void
     */
    public function handle(NewlinkNotifyEvent $event)
    {
        Mail::to(SettingsHelpers::getSetting('EMAIL'))->send(new NewlinkNotify($event->links));
    }
}
