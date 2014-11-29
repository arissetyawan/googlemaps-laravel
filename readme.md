Google Maps Wrapper for Laravel 4
======================

This package is a Google Maps API wrapper
for [**Laravel 4**](http://laravel.com/).


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

Usage
-----

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

    'GoogleMaps' => 'Vipond\GoogleMaps\GoogleMapsFacade',
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


Example with Facade
-------------------

```php

// ...
try {
    $geocode = GoogleMaps::geocode('#301, 610 Victoria Street, New Westminster, BC, Canada');
    var_dump($geocode);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```


Support
-------

[Please open an issue on GitHub](https://github.com/anthonyvipond/googlemaps-laravel/issues)


License
-------

GoogleMaps-Laravel is released under the MIT License.