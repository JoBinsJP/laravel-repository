<?php

namespace JoBins\LaravelRepository\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use JoBins\LaravelRepository\Fractal\CustomParamBag;
use JoBins\LaravelRepository\Fractal\CustomScope;
use League\Fractal\ParamBag;
use League\Fractal\Scope;

/**
 * Class VendorOverrideServiceProvider
 *
 * @package JoBins\LaravelRepository\Providers
 */
class VendorOverrideServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias(ParamBag::class, CustomParamBag::class);
        $loader->alias(Scope::class, CustomScope::class);
    }
}
