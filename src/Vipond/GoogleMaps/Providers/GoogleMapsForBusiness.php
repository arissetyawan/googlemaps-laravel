<?php

namespace Vipond\GoogleMaps\Providers;

class GoogleMapsForBusiness extends GoogleMaps {
    
    protected $privateKey;

    public function __construct($adapter, $ssl, $clientId, $privateKey)
    {
        $this->clientId = $clientId;
        $this->privateKey = $privateKey;

        parent::__construct($adapter, $ssl);
    }

    /**
     * Sign a URL with a given crypto key
     * Note that this URL must be properly URL-encoded
     *
     * @param string $query Query to be signed
     *
     * @return string $query Query with signature appended.
     */
    protected function signQuery($query)
    {
        $url = parse_url($query);

        $urlPartToSign = $url['path'] . '?' . $url['query'];

        // Decode the private key into its binary format
        $decodedKey = base64_decode(str_replace(array('-', '_'), array('+', '/'), $this->privateKey));

        // Create a signature using the private key and the URL-encoded
        // string using HMAC SHA1. This signature will be binary.
        $signature = hash_hmac('sha1', $urlPartToSign, $decodedKey, true);

        $encodedSignature = str_replace(array('+', '/'), array('-', '_'), base64_encode($signature));

        return sprintf('%s&signature=%s', $query, $encodedSignature);
    }

}