<?php

namespace App\Infrastructure\Communication\Http;

class HttpClientHttpie implements HttpClientInterface
{
    public function send($method, $url, $payload)
    {
        if ('GET' === $method) {
            $command = sprintf('http -b %s %s', $method, $url);
        } else {
            $command = sprintf('echo \'%s\' | http -b %s %s', json_encode($payload), $method, $url);
        }
        $return = shell_exec($command);

        return json_decode($return);
    }
}
