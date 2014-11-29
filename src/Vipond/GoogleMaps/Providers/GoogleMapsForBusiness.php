<?php

namespace Vipond\GoogleMaps\Providers;

class GoogleMapsForBusiness extends GoogleMaps {
    
    protected $privateKey;

    public function __construct($adapter, $ssl, $clientId, $privateKey, $language = null)
    {
        $this->clientId = $clientId;
        $this->privateKey = $privateKey;

        parent::__construct($adapter, $ssl, $language);
    }

    /**
     * Sign a URL with a given crypto key
     * This URL must be properly URL-encoded
     *
     * @param string $url Url to be signed
     *
     * @return string $url Url
     */
    protected function signUrl($url)
    {
        $parts = parse_url($url);

        $urlPartToSign = $parts['path'] . '?' . $parts['query'];

        $decodedKey = base64_decode(str_replace(array('-', '_'), array('+', '/'), $this->privateKey));

        $signature = hash_hmac('sha1', $urlPartToSign, $decodedKey, true);

        $encodedSignature = str_replace(array('+', '/'), array('-', '_'), base64_encode($signature));

        return sprintf('%s&signature=%s', $url, $encodedSignature);
    }

}