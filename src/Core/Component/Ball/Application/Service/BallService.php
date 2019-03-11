<?php

namespace App\Core\Component\Ball\Application\Service;

use App\Core\Component\Ball\Domain\Ball;
use App\Infrastructure\WebSocket\Client\WsClientInterface;
use Psr\Log\LoggerInterface;

class BallService
{
    public function __construct(LoggerInterface $logger, WsClientInterface $WsClient)
    {
        $this->logger = $logger;
        $this->WsClient = $WsClient;
    }

    public function run(Ball $ball)
    {
        $this->logger->info(sprintf('ball run @ speed %s', $ball->getSpeed()));
        if ($ball->getSpeed() > 0) {
            $ball->setAngle($ball->getPosition()->move($ball->getAngle(), $ball->getSpeed(), 80, 100));
            $ball->setSpeed(max($ball->getSpeed() - 0.5, 0));
        }
        $this->WsClient->send('broadcast:'.$ball->serialize());
    }
}
