<?php

namespace App\Controller;

use App\Entities\Ball;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BallController extends Controller
{
    /** @var @Ball $ball */
    var $ball;

    /** @var LoggerInterface */
     var $log;

    function __construct(Ball $ball,LoggerInterface $log)
    {
        $this->ball = $ball;
        $this->log = $log;
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
     * @Route("/ball/hit/{x}/{y}/{strength}/{uuid}/{name}",methods={"PUT"})
     */
    public function hitFrom($x,$y,$strength,$uuid,$name)
    {
        $this->log->debug(sprintf('hit from %s/%s@%s %s::%s',$x,$y,$strength,$uuid,$name));
        $this->ball->load();
        $this->ball->hitFrom(new Point($x,$y),$strength);

        return new JsonResponse([
            'x' => $this->ball->getPosition()->getX(),
            'y' => $this->ball->getPosition()->getY()
        ]);
    }
}
