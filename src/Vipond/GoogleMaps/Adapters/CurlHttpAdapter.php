<?php

namespace Vipond\GoogleMaps\Adapters;

class CurlHttpAdapter {

    public function __construct($ssl = false)
    {
        $this->useSsl = $ssl;

        $ch = curl_init();
    }

    public function get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        // so curl_exec returns the result instead of outputting it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->useSsl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        return curl_exec($ch);
    }

    public function __destruct()
    {
        curl_close($ch);
    }
}