<?php

namespace App\Infrastructure\Data;

use Ramsey\Uuid\Uuid as VendorUuid;

final class Uuid
{
    public function __invoke()
    {
        return VendorUuid::uuid4();
    }
}
