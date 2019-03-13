<?php

namespace App\Core\Port\TemplateEngine;

use Symfony\Component\HttpFoundation\Response;

interface TemplateEngineInterface
{
    public function renderResponse(
        string $template,
        array $parameters = [],
        Response $response = null
    ): Response;
}
