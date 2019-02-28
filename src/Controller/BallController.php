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
     * @Route("/ball/position")
     */
    public function position()
    {

        return new JsonResponse(['x' => $this->ball->getPosition()->getY(),'y' => $this->ball->getPosition()->getY()]);
    }
}
