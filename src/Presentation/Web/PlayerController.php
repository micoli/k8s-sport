<?php

namespace App\Presentation\Web;

use App\Core\Component\Player;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayerController extends Controller
{
    /** @var Player $player */
    public $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->player->load();
    }

    /**
     * @Route("/player/position")
     */
    public function position()
    {
        return new JsonResponse(['x' => $this->player->getPosition()->getY(), 'y' => $this->player->getPosition()->getY()]);
    }

    /**
     * @Route("/player")
     */
    public function player()
    {
        return new JsonResponse(['player' => $this->player->serialize()]);
    }

    /**
     * @Route("/player/env")
     */
    public function playerEnv()
    {
        return new JsonResponse(['env' => getenv()]);
    }
}
