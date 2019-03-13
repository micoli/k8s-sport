<?php

namespace App\Infrastructure\WebSocket\Client;

/*
    based on: http://stackoverflow.com/questions/7160899/websocket-client-in-php/16608429#16608429
    FIRST EXEC PYTHON SCRIPT TO GET HEADERS
*/

use App\Core\Port\Notification\NotificationEmitterInterface;

final class WsClient implements NotificationEmitterInterface
{
    private $head;
    private $instance;
    private $host;
    private $port;
    private $path;

    public function __construct($host, $port, $path)
    {
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;

        $local = 'http://'.$this->host;
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $local = 'http://'.$_SERVER['REMOTE_ADDR'];
        }

        $this->head = "GET / HTTP/1.1\r\n".
            "Upgrade: websocket\r\n".
            "Connection: Upgrade\r\n".
            'Host: '.$this->host."\r\n".
            'Origin: '.$local."\r\n".
            "Sec-WebSocket-Key: TyPfhFqWTjuw8eDAxdY8xg==\r\n".
            "Sec-WebSocket-Version: 13\r\n";
    }

    public function send($method)
    {
        $this->head .= 'Content-Length: '.strlen($method)."\r\n\r\n";
        if ($this->connect()) {
            fwrite($this->instance, $this->hybi10Encode($method));
            $wsdata = fread($this->instance, 2000);

            return $this->hybi10Decode($wsdata);
        }

        return false;
    }

    private function connect()
    {
        $sock = @fsockopen($this->host, $this->port, $errno, $errstr, 2);
        if (false === $sock) {
            return false;
        }
        fwrite($sock, $this->head);
        $headers = fread($sock, 2000);
        $this->instance = $sock;

        return true;
    }

    private function hybi10Encode($payload, $type = 'text', $masked = true)
    {
        $frameHead = [];
        $frame = '';
        $payloadLength = strlen($payload);
        switch ($type) {
            case 'text':
                // first byte indicates FIN, Text-Frame (10000001):
                $frameHead[0] = 129;
                break;
            case 'close':
                // first byte indicates FIN, Close Frame(10001000):
                $frameHead[0] = 136;
                break;
            case 'ping':
                // first byte indicates FIN, Ping frame (10001001):
                $frameHead[0] = 137;
                break;
            case 'pong':
                // first byte indicates FIN, Pong frame (10001010):
                $frameHead[0] = 138;
                break;
        }
        // set mask and payload length (using 1, 3 or 9 bytes)
        if ($payloadLength > 65535) {
            $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
            $frameHead[1] = (true === $masked) ? 255 : 127;
            for ($i = 0; $i < 8; ++$i) {
                $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
            }
            // most significant bit MUST be 0 (close connection if frame too big)
            if ($frameHead[2] > 127) {
                $this->close(1004);

                return false;
            }
        } elseif ($payloadLength > 125) {
            $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
            $frameHead[1] = (true === $masked) ? 254 : 126;
            $frameHead[2] = bindec($payloadLengthBin[0]);
            $frameHead[3] = bindec($payloadLengthBin[1]);
        } else {
            $frameHead[1] = (true === $masked) ? $payloadLength + 128 : $payloadLength;
        }
        // convert frame-head to string:
        foreach (array_keys($frameHead) as $i) {
            $frameHead[$i] = chr($frameHead[$i]);
        }
        if (true === $masked) {
            // generate a random mask:
            $mask = [];
            for ($i = 0; $i < 4; ++$i) {
                $mask[$i] = chr(rand(0, 255));
            }
            $frameHead = array_merge($frameHead, $mask);
        }
        $frame = implode('', $frameHead);
        // append payload to frame:
        for ($i = 0; $i < $payloadLength; ++$i) {
            $frame .= (true === $masked) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        }

        return $frame;
    }

    public function close()
    {
        if ($this->instance) {
            fclose($this->instance);
            $this->instance = null;
        }
    }

    private function hybi10Decode($data)
    {
        $bytes = $data;
        $dataLength = '';
        $mask = '';
        $coded_data = '';
        $decodedData = '';
        $secondByte = sprintf('%08b', ord($bytes[1]));
        $masked = ('1' == $secondByte[0]) ? true : false;
        $dataLength = (true === $masked) ? ord($bytes[1]) & 127 : ord($bytes[1]);
        if (true === $masked) {
            if (126 === $dataLength) {
                $mask = substr($bytes, 4, 4);
                $coded_data = substr($bytes, 8);
            } elseif (127 === $dataLength) {
                $mask = substr($bytes, 10, 4);
                $coded_data = substr($bytes, 14);
            } else {
                $mask = substr($bytes, 2, 4);
                $coded_data = substr($bytes, 6);
            }
            for ($i = 0; $i < strlen($coded_data); ++$i) {
                $decodedData .= $coded_data[$i] ^ $mask[$i % 4];
            }
        } else {
            if (126 === $dataLength) {
                $decodedData = substr($bytes, 4);
            } elseif (127 === $dataLength) {
                $decodedData = substr($bytes, 10);
            } else {
                $decodedData = substr($bytes, 2);
            }
        }

        return $decodedData;
    }

    public function broadcast($message)
    {
        $this->send($message);
        $this->close();
    }
}
