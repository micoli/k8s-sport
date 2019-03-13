<?php

namespace App\Infrastructure\Communication\Http;

use App\Core\Port\ServiceAccess\ServiceAccessInterface;

final class HttpClientCurl implements ServiceAccessInterface
{
    public function get($method)
    {
        return $this->send('GET', $method, null);
    }

    private function send($method, $url, $payload)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        if ('GET' != $method) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }
        $return = curl_exec($curl);
        curl_close($curl);

        return json_decode((string)$return);
    }

    public function put($method, $payload)
    {
        return $this->send('PUT', $method, $payload);
    }

    public function post($method, $payload)
    {
        return $this->send('POST', $method, $payload);
    }
}
