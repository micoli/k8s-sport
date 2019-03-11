<?php

namespace App\Infrastructure\Communication\Http;

class HttpClientCurl implements HttpClientInterface
{
    public function send($method, $url, $payload)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        if ('GET' != $method) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }
        $return = curl_exec($curl);
        curl_close($curl);

        return json_decode($return);
    }
}
