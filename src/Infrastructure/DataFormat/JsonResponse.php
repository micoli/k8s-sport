<?php

namespace App\Infrastructure\DataFormat;

use App\Core\Port\DataFormat\ApiResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse implements ApiResponseInterface
{
    public function generate($data = null, $status = 200): Response
    {
        return new SymfonyJsonResponse($data, $status);
    }
}
