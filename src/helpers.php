<?php

use GSMeira\LaravelMultialerts\LaravelMultialerts;

if (! function_exists('multialerts'))
{
    /**
     * Returns an instance of Multialerts.
     *
     * @param string $type
     * @return LaravelMultialerts
     */
    function multialerts($type = 'default')
    {
        return app(LaravelMultialerts::class, [ $type ]);
    }
}