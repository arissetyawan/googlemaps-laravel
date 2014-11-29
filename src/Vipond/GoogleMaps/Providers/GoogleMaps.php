<?php

namespace Vipond\GoogleMaps\Providers;

use Vipond\Providers\ApiService;
use Vipond\GoogleMaps\Entities\Location;

class GoogleMaps {
    
    const ENDPOINT_URL = 'http://maps.googleapis.com/maps/api/geocode/json?address=';

    const ENDPOINT_URL_SSL = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

    public function __construct($adapter, $ssl)
    {
        $this->useSsl = $ssl;
        $this->adapter = new $adapter($ssl);
        $this->location = new Location;
    }

    /**
     * @param string $query
     *
     * @return string Query with extra params
     */
    protected function buildQuery($query)
    {
        if ( $this->useSsl ) {
            $url = self::ENDPOINT_URL;
        } else {
            $url = self::ENDPOINT_URL_SSL;
        }

        $query = $url . urlencode($query) . '&client=' . $this->clientId;

        $signedQuery = $this->signQuery($query);

        return $signedQuery;
    }

    /**
     * @param string $location
     *
     * @return string JSON results
     */
    public function geocode($location)
    {
        $query = $this->buildQuery($location, $this->useSsl);

        $content = $this->getContent($query);

        $content = json_decode($content);

        if ($content->status === 'OK') {
            return $this->formatResult($content->results[0]->address_components);
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

        throw new Exception("Error Processing Request: " . __METHOD__);
    }

    protected function getContent($query)
    {
        return $this->adapter->get($query);
    }

    protected function formatResult(array $results)
    {
        $location = [];

        foreach ($results as $result) {
            foreach ($result->types as $prop) {
                if (property_exists($this->location, $prop)) {
                    $location[$prop]['long_name'] = $result->long_name;
                    $location[$prop]['short_name'] = $result->short_name;
                    break;
                } else {
                    return $results;
                    throw new \Exception("Unknown property" . $prop);
                }
            }
        }
    
        return $location;
    }
}