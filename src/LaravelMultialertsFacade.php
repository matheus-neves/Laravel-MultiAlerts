<?php

namespace GSMeira\LaravelMultialerts;

use Illuminate\Support\Facades\Facade;

class LaravelMultialertsFacade extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-multialerts';
    }
}