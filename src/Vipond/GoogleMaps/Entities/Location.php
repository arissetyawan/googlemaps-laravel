<?php

namespace Vipond\GoogleMaps\Entities;

/**
 * Properties are named to match Google's format
 */
class Location {

    protected $country;
    protected $administrative_area_level_1;
    protected $administrative_area_level_2;
    protected $administrative_area_level_3;
    protected $locality;
    protected $sublocality_level_1;
    protected $sublocality_level_2;
    protected $route;
    protected $neighborhood;
    protected $street_number;
    protected $subpremise;
    protected $premise;
    protected $postal_code;
    protected $postal_code_suffix;
    
    protected $point_of_interest;
    protected $natural_feature;
    protected $establishment;
}