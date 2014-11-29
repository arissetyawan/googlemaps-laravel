Google Maps Wrapper for Laravel 4
======================

This package is a Google Maps API wrapper
for [**Laravel 4**](http://laravel.com/).

Preface
------------

I hear you saying, **"why should I use this when I can use the popular [**Geocoder**](https://github.com/geocoder-php/Geocoder) library?"**

Short answer: It is **not good** for geocoding non-US properties. If you don't believe me, check its source. If they change this in the future, let me know.

This library gives you the **closest match Google returns**, does **not rename keys**, and **leaves no location properties out**. It only rearranges the response structure to make it easier to work with.

```php
array (size=8)
  'subpremise' => 
    array (size=2)
      'long_name' => string '301' (length=3)
      'short_name' => string '301' (length=3)
  'street_number' => 
    array (size=2)
      'long_name' => string '610' (length=3)
      'short_name' => string '610' (length=3)
  'route' => 
    array (size=2)
      'long_name' => string 'Victoria Street' (length=15)
      'short_name' => string 'Victoria St' (length=11)
  'locality' => 
    array (size=2)
      'long_name' => string 'New Westminster' (length=15)
      'short_name' => string 'New Westminster' (length=15)
  'administrative_area_level_2' => 
    array (size=2)
      'long_name' => string 'Greater Vancouver' (length=17)
      'short_name' => string 'Greater Vancouver' (length=17)
  'administrative_area_level_1' => 
    array (size=2)
      'long_name' => string 'British Columbia' (length=16)
      'short_name' => string 'BC' (length=2)
  'country' => 
    array (size=2)
      'long_name' => string 'Canada' (length=6)
      'short_name' => string 'CA' (length=2)
  'postal_code' => 
    array (size=2)
      'long_name' => string 'V3L 1C5' (length=7)
      'short_name' => string 'V3L 1C5' (length=7)
```

Installation
------------

The recommended way is through [composer](http://getcomposer.org).

Edit `composer.json` and add:

```json
{
    "require": {
        "anthonyvipond/googlemaps-laravel": "dev-master"
    }
}
```

And install dependencies:

```bash
    composer install
```

Find the `providers` key in `app/config/app.php` and register the **GoogleMaps Service Provider**.

```php
'providers' => array(
    // ...

    'Vipond\GoogleMaps\GoogleMapsServiceProvider',
)
```

Find the `aliases` key in `app/config/app.php` and register the **Geocoder Facade**.

```php
'aliases' => array(
    // ...

    'GoogleMaps' => 'Vipond\GoogleMaps\GoogleMapsFacade', // don't like this alias? change it.
)
```

Configuration
-------------

Publish and edit the configuration file

```bash
    php artisan config:publish anthonyvipond/googlemaps-laravel
```

```php
return [
    'provider'    => 'GoogleMapsForBusiness', // or 'GoogleMaps' for free accounts
    'adapter'     => 'CurlHttpAdapter',
    'ssl'         => true,
    'client-id'   => 'google-client-id',
    'private-key' => 'google-private-key',
];
```

Usage
-----

Geocoding an address
```php
    $geocode = GoogleMaps::geocode('The White House, Washington, DC');
    var_dump($geocode);
```

Reverse geocoding
```php
    $coords = '+40.689060,-74.044636';
    $geocode = GoogleMaps::geocode($coords);
    var_dump($geocode);
```

Use filters to speed up the request and not get unwanted results
```php
$filters = [
    'country' => 'USA',
    'locality' => 'Washington',
];

$results = GoogleMaps::geocode('160 Pine Street', $filters);
```

All available geocoding filters
```php
$filters = [
    'route' => 'Robson Street', // i.e. Robson Street **NOT** 576 Robson Street
    'locality' => 'Vancouver', // a locality or sublocality
    'administrative_area' => 'Greater Vancouver',
    'postal_code' => 'V3M 0A5', // postal_code or postal_code_prefix (zipcodes, too)
    'country' => 'CA', // country name or a two letter ISO 3166-1 country code.
];

// Parameters can be specified in the address parameter or as a filter, but not both!
$results = GoogleMaps::geocode('McDonalds', $filters);
```

Support
-------

[Please open an issue on GitHub](https://github.com/anthonyvipond/googlemaps-laravel/issues)


License
-------

GoogleMaps-Laravel is released under the MIT License.