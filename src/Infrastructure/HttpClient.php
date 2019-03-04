<?php
/**
 * Created by PhpStorm.
 * User: o.michaud
 * Date: 28/02/19
 * Time: 13:29
 */

namespace App\Infrastructure;

use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpClient implements HttpClientInterface
{

    public function send($method, $url, $payload)
    {
        try{
            $client = new Client();
            if ($payload === null) {
                $response = $client->request($method, $url);
            } else {
                $response = $client->request($method, $url, ['json'=>$payload]);
            }

            //print $response->getBody()->getContents();
            return json_decode($response->getBody()->getContents());
        }catch(RequestException $e){
            return null;//print $e->getResponse()->getBody()->getContents();
        }
    }
    /*
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');

    echo $response->getStatusCode(); # 200
    echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
    echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'

    # Send an asynchronous request.
    $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
    $promise = $client->sendAsync($request)->then(function ($response) {
        echo 'I completed! ' . $response->getBody();
    });

    $promise->wait();
     */
}
