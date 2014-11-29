<?php namespace Vipond\GoogleMaps;

use Illuminate\Support\ServiceProvider;

class GoogleMapsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('anthonyvipond/googlemaps-laravel');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('vipond.googlemaps', function($app) {
            $provider   = 'Vipond\GoogleMaps\Providers\\' . $app['config']->get('googlemaps-laravel::provider');
            $adapter    = 'Vipond\GoogleMaps\Adapters\\' . $app['config']->get('googlemaps-laravel::adapter');
            $ssl        = $app['config']->get('googlemaps-laravel::ssl');
            $clientId   = $app['config']->get('googlemaps-laravel::client-id');
            $privateKey = $app['config']->get('googlemaps-laravel::private-key');
            $language   = $app['config']->get('googlemaps-laravel::language');

            return new $provider($adapter, $ssl, $clientId, $privateKey, $language);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('vipond.googlemaps');
	}

}
