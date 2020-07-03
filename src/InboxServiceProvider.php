<?php

namespace Multicaret\Inbox;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class InboxServiceProvider extends ServiceProvider
{
    use EventMap;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerEvents();
        $this->registerMigrations();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerTranslations();
    }

    /**
     * Register the Inbox events.
     *
     * @return void
     */
    protected function registerEvents()
    {
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Register the Inbox routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('inbox.route.prefix', 'inbox'),
            'namespace' => 'Multicaret\Inbox\Http\Controllers',
            'middleware' => config('inbox.route.middleware', ['web', 'auth']),
            'as' => config('inbox.route.name')
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the Inbox resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'inbox');
    }

    /**
     * Register Inbox's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register Inbox's translations files.
     *
     * @return void
     */
    protected function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'inbox');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->offerPublishing();
    }

    /**
     * Setup the configuration for Inbox.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/inbox.php', 'inbox'
        );
    }

    /**
     * Setup the resource publishing groups for Inbox.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/inbox.php' => config_path('inbox.php'),
            ], 'inbox-config');

//            $this->publishes([
//                __DIR__ . '/../database/migrations' => database_path('migrations'),
//            ], 'inbox-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/inbox'),
            ], 'inbox-views');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/inbox'),
            ], 'inbox-translations');
        }
    }
}
