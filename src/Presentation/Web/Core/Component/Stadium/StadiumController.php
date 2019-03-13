<?php

namespace App\Presentation\Web\Core\Component\Stadium;

use App\Core\Component\Stadium\Application\Repository\StadiumRepositoryInterface;
use App\Core\Port\DataFormat\ApiResponseInterface;
use App\Core\Port\TemplateEngine\TemplateEngineInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

final class StadiumController
{
    /** @var StadiumRepositoryInterface */
    private $stadiumRepository;

    /** @var TemplateEngineInterface */
    private $templateEngine;

    /** @var ApiResponseInterface */
    private $apiResponse;

    public function __construct(StadiumRepositoryInterface $stadiumRepository, LoggerInterface $logger, TemplateEngineInterface $templateEngine, ApiResponseInterface $apiResponse)
    {
        $this->stadiumRepository = $stadiumRepository;
        $this->apiResponse = $apiResponse;
        $this->templateEngine = $templateEngine;
        $this->logger = $logger;
    }

    /**
     * @Route("/")
     * @Route("/stadium")
     */
    public function default()
    {
        $stadium = $this->stadiumRepository->get();

        return $this->templateEngine->renderResponse('@Stadium/stadium.html.twig', [
            'surface' => $stadium->getSurface(),
        ]);
    }

    /**
     * @Route("/stadium/surface")
     */
    public function getSurface()
    {
        $stadium = $this->stadiumRepository->get();

        return $this->apiResponse->generate([
            'surface' => $stadium->getSurface(),
        ]);
    }

    /**
     * @Route("/stadium/distributePlayer/{team}", methods={"GET"})
     */
    public function distributePlayer($team)
    {
        $stadium = $this->stadiumRepository->get();

        list($name, $icon) = $stadium->distributePlayer($team);

        $this->stadiumRepository->update($stadium);

        return $this->apiResponse->generate(['name' => $name, 'icon' => $icon]);
    }
}
