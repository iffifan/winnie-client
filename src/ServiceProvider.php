<?php

namespace Iffifan\WinnieClient;

use Illuminate\Http\Client\Factory;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $configPath = __DIR__.'/../config/winnie-client.php';
        $this->mergeConfigFrom($configPath, 'winnie-client');
        $this->app->singleton(WinnieClient::class, function ($app) {
            return new WinnieClient($app, $app->make(Factory::class));
        });
        $this->app->alias(WinnieClient::class, 'winnie_client');
    }

    public function boot()
    {
        $configPath = __DIR__.'/../config/winnie-client.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    protected function getConfigPath()
    {
        return config_path('winnie-client.php');
    }

}
