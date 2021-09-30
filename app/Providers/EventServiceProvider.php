<?php

namespace App\Providers;

use App\Events\FeedbackMailEvent;
use App\Listeners\FeedbackMailListener;
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
        FeedbackMailEvent::class => [ // событие при отправке формы
            FeedbackMailListener::class, // слушатель этого события
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
