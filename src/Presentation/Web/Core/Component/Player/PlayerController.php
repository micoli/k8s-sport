<?php

namespace App\Presentation\Web\Core\Component\Player;

use App\Core\Component\Player\Application\Repository\PlayerRepositoryInterface;
use App\Core\Port\DataFormat\ApiResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

final class PlayerController
{
    /** @var PlayerRepositoryInterface $player */
    public $playerRepository;

    /** @var LoggerInterface */
    public $logger;

    /** @var ApiResponseInterface */
    private $apiResponse;

    public function __construct(PlayerRepositoryInterface $playerRepository, LoggerInterface $logger, ApiResponseInterface $apiResponse)
    {
        $this->playerRepository = $playerRepository;
        $this->logger = $logger;
        $this->apiResponse = $apiResponse;
    }

    /**
     * @Route("/player/position")
     */
    public function position()
    {
        $player = $this->playerRepository->get();

        return $this->apiResponse->generate(['x' => $player->getPosition()->getY(), 'y' => $player->getPosition()->getY()]);
    }

    /**
     * @Route("/player")
     */
    public function player()
    {
        $player = $this->playerRepository->get();

        return $this->apiResponse->generate(['player' => $player->serialize()]);
    }

    /**
     * @Route("/player/env")
     */
    public function playerEnv()
    {
        return $this->apiResponse->generate(['env' => getenv()]);
    }
}
