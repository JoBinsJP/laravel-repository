<?php

namespace JoBins\LaravelRepository;

use JoBins\LaravelRepository\Commands\LaravelRepositoryCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Class LaravelRepositoryServiceProvider
 *
 * @package JoBins\LaravelRepository
 */
class LaravelRepositoryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('laravel-repository')->hasConfigFile()->hasViews()->hasMigration(
            'create_laravel-repository_table'
        )->hasCommand(LaravelRepositoryCommand::class);
    }
}
