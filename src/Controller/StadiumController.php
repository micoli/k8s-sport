<?php

namespace App\Controller;

use App\Entities\Ball;
use App\Entities\Player;
use App\Entities\Stadium;
use App\Infrastructure\WsClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StadiumController extends Controller
{
    /** @var @Stadium $stadium */
    var $stadium;
    private $WsClient = null;

    function __construct(WsClient $WsClient)
    {
        $this->WsClient = $WsClient;
    }


    /**
     * @Route("/")
     * @Route("/stadium")
     */
    public function default(Stadium $stadium)
    {
        $stadium->load();
        return $this->render('stadium.html.twig', [
            'players' => $stadium->getPlayers(),
            'balls' => $stadium->getBalls(),
            'dimension' => $stadium->getDimension()
        ]);
    }

    /**
     * @Route("/stadium/dimension")
     */
    public function getDimension(Stadium $stadium)
    {
        $stadium->load();

        return new JsonResponse([
            'dimension' => $stadium->getDimension()
        ]);
    }

    /**
     * @Route("/stadium/ball", methods={"PUT"})
     */
    public function postBall(Request $request, Ball $ball, Stadium $stadium)
    {
        if ($content = $request->getContent()) {
            $stadium->load();
            $jsonData = json_decode($content);

            $ball->setUUID($jsonData->uuid);
            $ball->setPosition($jsonData->position->x, $jsonData->position->y);
            $stadium->setBall($ball);

            $this->WsClient->send('broadcast:' . json_encode($jsonData));
            $this->WsClient->close();

            return new JsonResponse(['success' => true]);
        }
        return new JsonResponse(['success' => false], 500);
    }

    /**
     * @Route("/stadium/ball/{id}", methods={"DELETE"})
     */
    public function deleteBall(Stadium $stadium, $id)
    {
        $stadium->load();
        $stadium->removeBall($id);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/stadium/player", methods={"PUT"})
     */
    public function postPlayer(Request $request, Player $player, Stadium $stadium)
    {
        if ($content = $request->getContent()) {
            $stadium->load();
            $jsonData = json_decode($content);

            $player->setUUID($jsonData->uuid);
            $player->setName($jsonData->name);
            $player->setTeam($jsonData->team);
            $player->setPosition($jsonData->position->x, $jsonData->position->y);
            $stadium->setPlayer($player);
            $this->WsClient->send('broadcast:' . json_encode($jsonData));
            $this->WsClient->close();

            return new JsonResponse(['success' => true]);
        }
        return new JsonResponse(['success' => false], 500);
    }

    /**
     * @Route("/stadium/player/{id}", methods={"DELETE"})
     */
    public function deletePlayer(Stadium $stadium, $id)
    {
        $stadium->load();
        $stadium->removePlayer($id);

        return new JsonResponse(['success' => true]);
    }

}
