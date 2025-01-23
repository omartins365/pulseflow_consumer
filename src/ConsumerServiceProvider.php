<?php

namespace GenioForge\Consumer;

use Illuminate\Support\ServiceProvider;
use GenioForge\Consumer\Repository\RepositoryProvider;

class ConsumerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the path of the configuration file shipping with the package.
     *
     * @return string
     */
    public function getConfigPath()
    {
        return dirname(__DIR__) . '/config/consumer.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig($this->getConfigPath());
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerServices();
        $this->registerAliases();
    }

    /**
     * Register a path to be published by the publish command.
     *
     * @param string $path
     * @param string $group
     * @return void
     */
    protected function publishConfig($path, $group = 'config')
    {
        $this->publishes([$path => $this->app['path.config'] . '/consumer.php'], $group);
    }

    /**
     * Register the default configuration.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'consumer');
    }

    /**
     * Register the Consumer instance.
     *
     * @return void
     */
    protected function registerServices()
    {
//        $this->app->bind(EasyAccessApi::class);
        // $this->app->bind(ConsumerException::class);
        $this->app->singleton('consumer', function ($app) {
            return RepositoryProvider::defautProvider();
        });
    }

    /**
     * Register class aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $this->app->alias('consumer', RepositoryProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'consumer',
        ];
    }
}
