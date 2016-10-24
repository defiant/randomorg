<?php

namespace RandomOrg\Facades;

use Illuminate\Support\Facades\Facade;

class Random extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'randomorg'; // IoC binding.
    }
}
