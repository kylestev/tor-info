<?php

namespace TorInfo\Support\Laravel;

use Illuminate\Support\ServiceProvider;
use TorInfo\Support\Laravel\Console\CacheTorIPs;

class TorInfoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([__DIR__.'/config/torinfo.php' => config_path('torinfo.php')]);

        $this->registerCommands();
    }

    private function registerCommands()
    {
        // Clean command
        $this->app['command.tor.cache'] = $this->app->share(function ($app) {
            return new CacheTorIPs();
        });

        $this->commands(
            'command.tor.cache'
        );
    }
}
