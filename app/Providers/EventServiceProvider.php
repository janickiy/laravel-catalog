<?php

namespace App\Providers;

use App\Events\FeedbackMailEvent;
use App\Events\NewlinkNotifyEvent;
use App\Listeners\FeedbackMailListener;
use App\Listeners\NewlinkNotifyEventListener;
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
     * Registers application events and their listeners.
     */
    public function boot(): void
    {
        //
    }
}
