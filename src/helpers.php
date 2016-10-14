<?php

/*
 * This file is part of Laravel-MultiAlerts.
 *
 * (c) Gustavo Meireles <gustavo@gsmeira.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use GSMeira\LaravelMultiAlerts\LaravelMultiAlerts;

if (! function_exists('multialerts')) {
    /**
     * Returns an instance of Laravel-MultiAlerts.
     *
     * @param string $type
     * @return LaravelMultiAlerts
     */
    function multialerts($type = 'default')
    {
        return app('laravel-multialerts')->type($type);
    }
}
