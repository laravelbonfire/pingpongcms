<?php namespace Pingpong\Cms\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        \Lang::addNamespace('core', __DIR__.'/../Resources/lang');

        \View::addNamespace('core', __DIR__.'/../Resources/views');

        $this->registerConfig();

        $this->listenRoutes();
    }

    protected function listenRoutes()
    {
        $this->app->booted(function ($app) {
            $router = $app['router'];

            $router->group([
                'prefix' => cms()->prefix(),
                'middleware' => config('cms.middleware'),
            ], function () use ($router) {
               event('cms.routes', $router);
            });
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Pingpong\Cms\Core\Contracts\Services\Cms',
            'Pingpong\Cms\Core\Services\Cms\Cms'
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('cms.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'cms'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
