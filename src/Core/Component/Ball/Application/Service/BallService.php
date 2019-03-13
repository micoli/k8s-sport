<?php

namespace App\Core\Component\Ball\Application\Service;

use App\Core\Component\Ball\Domain\Ball;
use App\Core\Port\Notification\NotificationEmitterInterface;
use Psr\Log\LoggerInterface;

final class BallService
{
    /** @var NotificationEmitterInterface */
    private $notificationEmitter;

    public function __construct(LoggerInterface $logger, NotificationEmitterInterface $notificationEmitter)
    {
        $this->logger = $logger;
        $this->notificationEmitter = $notificationEmitter;
    }

    public function run(Ball $ball)
    {
        $this->logger->info(sprintf('ball run @ speed %s', $ball->getSpeed()));
        if ($ball->getSpeed() > 0) {
            $ball->setAngle($ball->getPosition()->move($ball->getAngle(), $ball->getSpeed(), 80, 100));
            $ball->setSpeed(max($ball->getSpeed() - 0.5, 0));
        }
        $this->notificationEmitter->broadcast($ball->serialize());
    }
}
