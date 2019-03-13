<?php

namespace App\Core\Port\DataFormat;

use Symfony\Component\HttpFoundation\Response;

interface ApiResponseInterface
{
    public function generate($data = null, $status = 200): Response;
}
