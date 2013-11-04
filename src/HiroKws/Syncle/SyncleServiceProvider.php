<?php

namespace Syncle;

use Illuminate\Support\ServiceProvider;
use Syncle\Commands\SyncleCommand;

/**
 * A service provider for syncle deploy command execute system.
 */
class SyncleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['syncle.synclecommand'] = $this->app->share( function($app)
            {
                return new SyncleCommand;
            }
        );
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Define resources
        $this->package( 'hirokws/syncle' );

        // Set Command name from config.
        if( \Config::has( 'syncle::CommandName' ) )
        {
            $this->app['syncle.synclecommand']
                ->setName( \Config::get( 'syncle::CommandName' ) );
        }

        // Set Description from language files.
        $this->app['syncle.synclecommand']
            ->setDescription( \Lang::get( 'syncle::SyncleCommand.CommandDescription' ) );

        // Register Commands
        $this->commands( 'syncle.synclecommand' );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array( 'syncle.synclecommand' );
    }

}