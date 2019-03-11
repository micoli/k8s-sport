<?php

namespace App\Presentation\Web;

use App\Core\Component\Stadium;
use App\Infrastructure\WebSocket\Client\WsClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StadiumController extends Controller
{
    /** @var @Stadium $stadium */
    public $stadium;
    private $WsClient = null;

    public function __construct(WsClient $WsClient)
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
            'dimension' => $stadium->getDimension(),
        ]);
    }

    /**
     * @Route("/stadium/dimension")
     */
    public function getDimension(Stadium $stadium)
    {
        $stadium->load();

        return new JsonResponse([
            'dimension' => $stadium->getDimension(),
        ]);
    }

    /**
     * @Route("/stadium/distributePlayer/{team}", methods={"DELETE"})
     */
    public function distributePlayer(Stadium $stadium, $team)
    {
        return new JsonResponse(['name' => $stadium->distributePlayer($team)]);
    }
}
