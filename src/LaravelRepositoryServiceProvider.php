<?php

namespace JoBins\LaravelRepository;

use Illuminate\Support\ServiceProvider;
use JoBins\LaravelRepository\Providers\RepositoryEventServiceProvider;

/**
 * Class LaravelRepositoryServiceProvider
 *
 * @package JoBins\LaravelRepository
 */
class LaravelRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/repository.php' => config_path('repository.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/repository.php', 'repository');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RepositoryEventServiceProvider::class);
    }
}
