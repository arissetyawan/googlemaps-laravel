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
    protected $sublocality_level_3;
    protected $sublocality_level_4;
    protected $sublocality_level_5;
    protected $route;
    protected $neighborhood;
    protected $colloquial_area;
    protected $ward;
    protected $street_number;
    protected $subpremise;
    protected $premise;
    protected $intersection;
    protected $postal_code;
    protected $postal_code_suffix;
    
    // TODO: tweak logic on how to create formatted result set
    protected $point_of_interest;
    protected $natural_feature;
    protected $establishment;
    protected $airport;
    protected $park;
    protected $bus_station;
    protected $train_station;
    protected $transit_station;
}