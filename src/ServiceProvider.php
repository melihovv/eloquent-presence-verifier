<?php

namespace Melihovv\EloquentPresenceVerifier;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/eloquent-presence-verifier.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('eloquent-presence-verifier.php'),
        ], 'config');

        $this->app['validator']->setPresenceVerifier($this->app['eloquent-presence-verifier']);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'eloquent-presence-verifier'
        );

        $this->app->bind('eloquent-presence-verifier', function ($app) {
            $model = config('eloquent-presence-verifier.model') ?: TempModel::class;

            return new EloquentPresenceVerifier(new $model, $app['db']);
        });
    }

    public function provides()
    {
        return [
            'eloquent-presence-verifier',
        ];
    }
}
