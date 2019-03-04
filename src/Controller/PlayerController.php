<?php

namespace App\Controller;

use App\Entities\Ball;
use App\Entities\Player;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayerController extends Controller
{
    /** @var @Player $player */
    var $player;

    function __construct(Player $player)
    {
        $this->player = $player;
        $this->player->load();
    }

    /**
     * @Route("/player/position")
     */
    public function position()
    {
        return new JsonResponse(['x' => $this->player->getPosition()->getY(),'y' => $this->player->getPosition()->getY()]);
    }
}
