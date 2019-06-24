<?php

namespace Braceyourself\ApiVendor;

use Braceyourself\ApiVendor\Commands\ApiVendorMakeCommand;
use Illuminate\Support\ServiceProvider;

class ApiVendorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/api-vendors.php' => config_path('api-vendors.php'),
            ], 'config');

            $this->commands([
                ApiVendorMakeCommand::class,
            ]);
        }

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api-vendors.php', 'api-vendors');
    }
}
