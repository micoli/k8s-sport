<?php

namespace App\Presentation\Web;

use App\Core\Component\Ball;
use App\Core\Component\Point;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BallController extends Controller
{
    /** @var @Ball $ball */
    public $ball;

    /** @var LoggerInterface */
    public $logger;

    public function __construct(Ball $ball, LoggerInterface $logger)
    {
        $this->ball = $ball;
        $this->logger = $logger;
    }

    /**
     * @Route("/ball/position",methods={"GET"})
     */
    public function position()
    {
        $this->ball->load();

        return new JsonResponse([
            'x' => $this->ball->getPosition()->getX(),
            'y' => $this->ball->getPosition()->getY(),
        ]);
    }

    /**
     * @Route("/ball/hitto/{x}/{y}/{strength}/{uuid}/{name}",methods={"PUT","GET"})
     */
    public function hitTo($x, $y, $strength, $uuid, $name)
    {
        $this->logger->info(sprintf('hit to %s/%s@%s %s::%s', $x, $y, $strength, $uuid, $name));
        $this->ball->load();
        $this->ball->hitTo(new Point($x, $y), $strength);

        return new JsonResponse([
            'x' => $this->ball->getPosition()->getX(),
            'y' => $this->ball->getPosition()->getY(),
        ]);
    }

    /**
     * @Route("/ball/hit/{x}/{y}/{strength}/{uuid}/{name}",methods={"PUT","GET"})
     */
    public function hitFrom($x, $y, $strength, $uuid, $name)
    {
        $this->logger->info(sprintf('hit from %s/%s@%s %s::%s', $x, $y, $strength, $uuid, $name));
        $this->ball->load();
        $this->ball->hitFrom(new Point($x, $y), $strength);

        return new JsonResponse([
            'x' => $this->ball->getPosition()->getX(),
            'y' => $this->ball->getPosition()->getY(),
        ]);
    }
}
