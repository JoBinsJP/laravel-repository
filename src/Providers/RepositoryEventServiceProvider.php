<?php

namespace JoBins\LaravelRepository\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryEventServiceProvider
 *
 * @package JoBins\LaravelRepository\Providers
 */
class RepositoryEventServiceProvider extends ServiceProvider
{
    protected array $listen = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $events = app('events');

        collect($this->listen)->each(function (array $listeners, string $event) use ($events) {
            collect($listeners)->each(fn(string $listener) => $events->listen($event, $listener));
        });
    }
}
