<?php

namespace RandomOrg;

use Illuminate\Support\ServiceProvider;

class RandomServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $path = realpath(__DIR__.'/../../config/randomorg.php');

        $this->publishes([
            $path => config_path('randomorg.php')
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('randomorg', function() {
          return new Random(config('randomorg.apiKey'));
        });

    }

}
