<?php

namespace App\Controller;

use App\Entities\Ball;
use App\Entities\Player;
use App\Entities\Stadium;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StadiumController extends Controller
{
    /** @var @Stadium $stadium */
    var $stadium;

    function __construct(Stadium $stadium)
    {
        $this->stadium = $stadium;
        $this->stadium->load();
    }

    /**
     * @Route("/")
     * @Route("/stadium")
     */
    public function default()
    {
        print_r([
            'players' => $this->stadium->getPlayers(),
            'balls' => $this->stadium->getBalls()
        ]);
        return $this->render('stadium.html.twig', [
            'players' => $this->stadium->getPlayers(),
            'balls' => $this->stadium->getBalls(),
            'dimension' => $this->stadium->getDimension()
        ]);
    }

    /**
     * @Route("/stadium/ball", methods={"PUT"})
     */
    public function postBall(Request $request,Ball $ball)
    {
        if ($content = $request->getContent()) {
            $jsonData = json_decode($content);

            $ball->setUUID($jsonData->uuid);
            $ball->setPosition($jsonData->position->x, $jsonData->position->y);
            $this->stadium->setBall($ball);

            return new JsonResponse(['success'=>true]);
        }
        return new JsonResponse(['success'=>false], 500);
    }

    /**
     * @Route("/stadium/ball/{id}", methods={"DELETE"})
     */
    public function deleteBall($id)
    {
        $this->stadium->removeBall($id);

        return new JsonResponse(['success'=>true]);
    }

    /**
     * @Route("/stadium/player", methods={"PUT"})
     */
    public function postPlayer(Request $request,Player $player)
    {
        if ($content = $request->getContent()) {
            $jsonData = json_decode($content);

            $player->setUUID($jsonData->uuid);
            $player->setName($jsonData->name);
            $player->setPosition($jsonData->position->x, $jsonData->position->y);
            $this->stadium->setPlayer($player);

            return new JsonResponse(['success'=>true]);
        }
        return new JsonResponse(['success'=>false], 500);
    }

    /**
     * @Route("/stadium/player/{id}", methods={"DELETE"})
     */
    public function deletePlayer($id)
    {
        $this->stadium->removePlayer($id);

        return new JsonResponse(['success'=>true]);
    }

}
