<?php

namespace Vipond\GoogleMaps\Providers;

use Vipond\GoogleMaps\Entities\Location;

class GoogleMaps {
    
    const ENDPOINT_URL = 'http://maps.googleapis.com/maps/api/geocode/json?address=';

    const ENDPOINT_URL_SSL = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

    protected $totalResults;

    public function __construct($adapter, $ssl, $language = null)
    {
        $this->useSsl = $ssl;
        $this->adapter = new $adapter($ssl);
        $this->language = $language;
        $this->location = new Location;
    }

    /**
     * @param string $query
     *
     * @return string Query with extra params
     */
    protected function buildQuery($query)
    {
        $url = $this->useSsl ? self::ENDPOINT_URL : self::ENDPOINT_URL_SSL;

        $url .= $query;

        $url .= $this->language ? '&language=' . $this->language : '';

        $url .= '&client=' . $this->clientId;


        return $this->signUrl($url);
    }

    /**
     * @param string $location
     *
     * @return mixed
     */
    public function geocode($query, $filters = null)
    {
        $components = $filters ? $this->getFilterString($filters) : null;
        
        $query = $this->buildQuery(urlencode(trim($query)) . $components, $this->useSsl);

        $content = json_decode($this->getContent($query));

        if ($content->status === 'OK') {
            $location = $this->formatAddress($content->results[0]->address_components);
        } else {
            if ( is_null($content) ) {
                return sprintf('Could not execute query: %s', $query);
            }

            if ('REQUEST_DENIED' === $content->status && 'The provided API key is invalid.' === $content->error_message) {
                return sprintf('API key is invalid: %s', $query);
            }

            if ('OVER_QUERY_LIMIT' === $content->status) {
                return sprintf('Over query limit: %s', $query);
            }
            
            if (strpos($content, "Provided 'signature' is not valid for the provided client ID") !== false) {
                return sprintf('Invalid client ID / API Key %s', $query);
            }
        }

        $location['formatted_address']  = $content->results[0]->formatted_address ?: null;

        $location['lat']  = $content->results[0]->geometry->location->lat ?: null;
        $location['long'] = $content->results[0]->geometry->location->lng ?: null;

        if (property_exists($content->results[0]->geometry, 'bounds')) {
            $box = 'bounds';
        } else {
            $box = 'viewport';
        }

        $location[$box]['ne-lat']  = $content->results[0]->geometry->$box->northeast->lat;
        $location[$box]['ne-long'] = $content->results[0]->geometry->$box->northeast->lng;
        $location[$box]['sw-lat']  = $content->results[0]->geometry->$box->southwest->lat;
        $location[$box]['sw-long'] = $content->results[0]->geometry->$box->southwest->lng;

        $location['types'] = $content->results[0]->types;
        
        return $location;
    }

    protected function getContent($query)
    {
        return $this->adapter->get($query);
    }

    protected function formatAddress(array $results)
    {
        $location = [];

        foreach ($results as $result) {
            foreach ($result->types as $prop) {
                if (property_exists($this->location, $prop)) {
                    $location[$prop]['long_name'] = $result->long_name;
                    $location[$prop]['short_name'] = $result->short_name;
                    break;
                }
                
                throw new \Exception("Unknown property: " . $prop);
            }
        }
    
        return $location;
    }

    protected function getFilterString($filters)
    {
        $filter = '';

        foreach ($filters as $key => $value) {
            $filter .= trim($key) . ':' . trim($value) . '|';
        }

        return '&components=' . rtrim($filter, '|');
    }

}