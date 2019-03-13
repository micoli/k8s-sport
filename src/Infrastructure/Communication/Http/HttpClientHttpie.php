<?php

namespace App\Infrastructure\Communication\Http;

use App\Core\Port\ServiceAccess\ServiceAccessInterface;

final class HttpClientHttpie implements ServiceAccessInterface
{
    public function get($service, $method)
    {
        return $this->send('GET', $service, $method, null);
    }

    private function send($verb, $service, $method, $payload)
    {
        $url = sprintf('http://%s/%s', $service, $method);
        if ('GET' === $verb) {
            $command = sprintf('http -b %s %s', $verb, $url);
        } else {
            $command = sprintf('echo \'%s\' | http -b %s %s', json_encode($payload), $verb, $url);
        }
        $return = shell_exec($command);

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
