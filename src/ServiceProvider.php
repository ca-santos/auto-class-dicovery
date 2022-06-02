<?php

namespace CaueSantos\AutoClassDiscovery;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/auto-class-discovery.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('auto-class-discovery.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'auto-class-discovery'
        );

        $this->app->bind('auto-class-discovery', function () {
            return new AutoClassDiscovery();
        });
    }
}
