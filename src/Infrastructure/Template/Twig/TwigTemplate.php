<?php

namespace App\Infrastructure\Template\Twig;

use App\Core\Port\TemplateEngine\TemplateEngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TwigTemplate implements TemplateEngineInterface
{
    /** @var Environment */
    private $engine;

    public function __construct(Environment $engine)
    {
        $this->engine = $engine;
    }

    public function renderResponse(
        string $template,
        array $parameters = [],
        Response $response = null
    ): Response {
        $content = $this->engine->render($template, $parameters);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }
}
