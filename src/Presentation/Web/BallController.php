<?php

namespace App\Presentation\Web;

use App\Core\Component\Ball\Application\Repository\BallRepositoryInterface;
use App\Core\SharedKernel\Component\Point;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BallController extends Controller
{
    /** @var BallRepositoryInterface $ball */
    public $ballRepository;

    /** @var LoggerInterface */
    public $logger;

    public function __construct(BallRepositoryInterface $ballRepository, LoggerInterface $logger)
    {
        $this->ballRepository = $ballRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/ball/position",methods={"GET"})
     */
    public function position()
    {
        $ball = $this->ballRepository->get();

        return new JsonResponse([
            'x' => $ball->getPosition()->getX(),
            'y' => $ball->getPosition()->getY(),
        ]);
    }

    /**
     * @Route("/ball/hitto/{x}/{y}/{strength}/{uuid}/{name}",methods={"PUT","GET"})
     */
    public function hitTo($x, $y, $strength, $uuid, $name)
    {
        $this->logger->info(sprintf('hit to %s/%s@%s %s::%s', $x, $y, $strength, $uuid, $name));
        $ball = $this->ballRepository->get();

        $ball->hitTo(new Point($x, $y), $strength);

        $this->ballRepository->update($ball);

        return new JsonResponse([
            'x' => $ball->getPosition()->getX(),
            'y' => $ball->getPosition()->getY(),
        ]);
    }

    /**
     * @Route("/ball/hit/{x}/{y}/{strength}/{uuid}/{name}",methods={"PUT","GET"})
     */
    public function hitFrom($x, $y, $strength, $uuid, $name)
    {
        $ball = $this->ballRepository->get();

        $this->logger->info(sprintf('hit from %s/%s@%s %s::%s', $x, $y, $strength, $uuid, $name));
        $ball->hitFrom(new Point($x, $y), $strength);

        $this->ballRepository->update($ball);

        return new JsonResponse([
            'x' => $ball->getPosition()->getX(),
            'y' => $ball->getPosition()->getY(),
        ]);
    }
}
