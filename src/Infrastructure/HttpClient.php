<?php
/**
 * Created by PhpStorm.
 * User: o.michaud
 * Date: 28/02/19
 * Time: 13:29
 */

namespace App\Infrastructure;
use \GuzzleHttp\Client;

class HttpClient implements HttpClientInterface
{

    public function send($method,$url,$payload){
        $client = new Client();
        $response = $client->request($method,$url);

        return json_decode($response->getBody()->getContents());
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
