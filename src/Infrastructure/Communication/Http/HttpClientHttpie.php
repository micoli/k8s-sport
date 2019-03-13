<?php

namespace App\Infrastructure\Communication\Http;

use App\Core\Port\ServiceAccess\ServiceAccessInterface;

final class HttpClientHttpie implements ServiceAccessInterface
{
    public function get($method)
    {
        return $this->send('GET', $method, null);
    }

    private function send($method, $url, $payload)
    {
        if ('GET' === $method) {
            $command = sprintf('http -b %s %s', $method, $url);
        } else {
            $command = sprintf('echo \'%s\' | http -b %s %s', json_encode($payload), $method, $url);
        }
        $return = shell_exec($command);

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
