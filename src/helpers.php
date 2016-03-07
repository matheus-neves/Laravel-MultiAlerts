<?php

/*
 * This file is part of Laravel Multialerts.
 *
 * (c) Gustavo Meireles <gustavo@gsmeira.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use GSMeira\LaravelMultialerts\LaravelMultialerts;

if (! function_exists('multialerts')) {
    /**
     * Returns an instance of Laravel Multialerts.
     *
     * @param string $type
     * @return LaravelMultialerts
     */
    function multialerts($type = 'default')
    {
        return app(LaravelMultialerts::class, [ $type ]);
    }
}