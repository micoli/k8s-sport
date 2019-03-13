<?php

namespace App\Core\Port\Notification;

interface NotificationEmitterInterface
{
    public function broadcast($message);
}
