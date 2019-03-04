<?php

namespace App\Controller;

use App\Entities\Ball;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BallController extends Controller
{
    /** @var @Ball $ball */
    var $ball;

    function __construct(Ball $ball)
    {
        $this->ball = $ball;
    }

    /**
     * @Route("/ball/position",methods={"GET"})
     */
    public function position()
    {
        $this->ball->load();
        return new JsonResponse([
            'x' => $this->ball->getPosition()->getX(),
            'y' => $this->ball->getPosition()->getY()
        ]);
    }

    /**
     * @Route("/ball/hit/{x}/{y}/{strength}",methods={"PUT"})
     */
    public function hitFrom($x,$y,$strength)
    {
        $this->ball->load();
        $this->ball->hitFrom(new Point($x,$y),$strength);

        return new JsonResponse([
            'x' => $this->ball->getPosition()->getX(),
            'y' => $this->ball->getPosition()->getY()
        ]);
    }
}
