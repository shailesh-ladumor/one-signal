<?php

namespace Ladumor\OneSignal;

use Illuminate\Support\ServiceProvider;
use Ladumor\OneSignal\commands\PublishUserDevice;

class OneSignalServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/one-signal.php';

        $this->publishes([
            $configPath => config_path('one-signal.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('one-signal', function ($app) {
            return new OneSignalManager();
        });

        $this->app->singleton('one-signal.userDevice:publish', function ($app) {
            return new PublishUserDevice();
        });

        $this->commands([
            'one-signal.userDevice:publish',
        ]);
    }
}
