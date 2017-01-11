<?php

/*
 * This file is part of Laravel-MultiAlerts.
 *
 * (c) Gustavo Meireles <gustavo@gsmeira.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GSMeira\LaravelMultiAlerts;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * This is the Laravel-MultiAlerts service provider class.
 *
 * @author Gustavo Meireles <gustavo@gsmeira.com>
 * @package GSMeira\LaravelMultiAlerts
 */
class ServiceProvider extends BaseServiceProvider
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
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('gsmeira/multialerts.php')
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $viewKey = $this->app->config->get('gsmeira.multialerts.view_key', 'multialerts');

        $this->app->view->share($viewKey, []);

        $this->app->bind('laravel-multialerts', function ($app) use ($viewKey) {
            $sessionKey = $app->config->get('gsmeira.multialerts.session_key', 'multialerts');
            $levels = $app->config->get('gsmeira.multialerts.levels', [ 'success', 'warning', 'error', 'info' ]);

            return new LaravelMultiAlerts($sessionKey, $viewKey, $levels);
        });
    }
}
