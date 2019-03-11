<?php

namespace App\Presentation\Web;

use App\Core\Component\Player\Application\Repository\PlayerRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayerController extends Controller
{
    /** @var PlayerRepositoryInterface $player */
    public $playerRepository;

    /** @var LoggerInterface */
    public $logger;

    public function __construct(PlayerRepositoryInterface $playerRepository, LoggerInterface $logger)
    {
        $this->playerRepository = $playerRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/player/position")
     */
    public function position()
    {
        $player = $this->playerRepository->get();

        return new JsonResponse(['x' => $player->getPosition()->getY(), 'y' => $player->getPosition()->getY()]);
    }

    /**
     * @Route("/player")
     */
    public function player()
    {
        $player = $this->playerRepository->get();

        return new JsonResponse(['player' => $player->serialize()]);
    }

    /**
     * @Route("/player/env")
     */
    public function playerEnv()
    {
        return new JsonResponse(['env' => getenv()]);
    }
}
