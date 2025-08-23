<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\NewsHidden::class => [
            \App\Listeners\NewsHiddenListener::class,
        ],
    ];

    public function boot(): void
    {
        // Регистрация наблюдателей перенесена в AppServiceProvider::boot()
    }
}
