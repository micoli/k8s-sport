<?php

namespace App\Infrastructure\Communication\Http;

use App\Core\Port\ServiceAccess\ServiceAccessInterface;

final class HttpClientCurl implements ServiceAccessInterface
{
    public function get($service, $method)
    {
        return $this->send('GET', $service, $method, null);
    }

    private function send($verb, $service, $method, $payload)
    {
        $url = sprintf('http://%s/%s', $service, $method);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $verb);

        if ('GET' != $verb) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }
        $return = curl_exec($curl);
        curl_close($curl);

        return json_decode((string) $return);
    }

    public function put($service, $method, $payload)
    {
        return $this->send('PUT', $service, $method, $payload);
    }

    public function post($service, $method, $payload)
    {
        return $this->send('POST', $service, $method, $payload);
    }
}
