<?php

namespace GSMeira\LaravelMultialerts;

use Illuminate\Support\ServiceProvider;

class LaravelMultialertsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // ...
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('laravel-multialerts',function($app)
        {
            return new LaravelMultialerts($app);
        });
    }
}