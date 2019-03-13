<?php

namespace App\Core\Component\Stadium\Application\Service;

use App\Core\Component\Stadium\Domain\Stadium;
use App\Core\Port\Notification\NotificationEmitterInterface;
use App\Core\Port\ServiceAccess\ServiceAccessInterface;
use Psr\Log\LoggerInterface;

final class StadiumService
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

    public function run(Stadium $stadium)
    {
    }
}
