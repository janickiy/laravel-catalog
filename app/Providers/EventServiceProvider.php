<?php

namespace App\Providers;

use App\Events\{FeedbackMailEvent,NewlinkNotifyEvent};
use App\Listeners\{FeedbackMailListener,NewlinkNotifyEventListener};
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        FeedbackMailEvent::class => [
            FeedbackMailListener::class,
        ],

        NewlinkNotifyEvent::class => [
            NewlinkNotifyEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
