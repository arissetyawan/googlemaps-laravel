<?php

namespace Vipond\GoogleMaps;

use Illuminate\Support\Facades\Facade;

class GoogleMapsFacade extends Facade
{
    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor()
    {
        return 'googlemaps';
    }
}
