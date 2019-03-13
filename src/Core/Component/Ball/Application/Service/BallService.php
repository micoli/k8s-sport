<?php

namespace App\Core\Component\Ball\Application\Service;

use App\Core\Component\Ball\Domain\Ball;
use App\Core\Port\Notification\NotificationEmitterInterface;
use App\Core\Port\ServiceAccess\ServiceAccessInterface;
use App\Core\SharedKernel\Component\Surface;
use Psr\Log\LoggerInterface;

final class BallService
{
    /** @var ServiceAccessInterface */
    private $serviceAccess;

    /** @var NotificationEmitterInterface */
    private $notificationEmitter;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger, NotificationEmitterInterface $notificationEmitter, ServiceAccessInterface $serviceAccess)
    {
        $this->logger = $logger;
        $this->notificationEmitter = $notificationEmitter;
        $this->serviceAccess = $serviceAccess;
    }

    public function init(Ball $ball)
    {
        if ([0, 0] === $ball->getConstraint()->getCoord()) {
            $struct = $this->serviceAccess->get('stadium-php', 'stadium/surface');
            if (isset($struct->surface)) {
                $ball->setConstraint(new Surface($struct->surface->width, $struct->surface->height));
            }
        }
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
