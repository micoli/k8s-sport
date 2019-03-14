<?php

namespace App\Core\Component\Ball\Application\Service;

use App\Core\Component\Ball\Domain\Ball;
use App\Core\Port\Notification\NotificationEmitterInterface;
use App\Core\Port\ServiceAccess\ServiceAccessInterface;
use App\Core\SharedKernel\Component\Point;
use App\Core\SharedKernel\Component\Surface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

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

    public function create(Ball $ball)
    {
        $ball->setUuid(Uuid::uuid4());
        $ball->setSpeed(0);
        $ball->setAngle(25);
        $ball->setCoordinates(new Point(0, 0));
        $ball->setConstraint(new surface(0, 0));

        $nb = 0;
        while ([0, 0] === $ball->getConstraint()->getCoord() && $nb++ < 10) {
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
            $ball->move();
            $ball->setSpeed(max($ball->getSpeed() - 0.5, 0));
        }
        $this->notificationEmitter->broadcast($ball->serialize());
    }
}
