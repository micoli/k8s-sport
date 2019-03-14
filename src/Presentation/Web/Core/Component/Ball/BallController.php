<?php

namespace App\Presentation\Web\Core\Component\Ball;

use App\Core\Component\Ball\Application\Repository\BallRepositoryInterface;
use App\Core\Port\DataFormat\ApiResponseInterface;
use App\Core\SharedKernel\Component\Point;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

final class BallController
{
    /** @var BallRepositoryInterface $ball */
    public $ballRepository;

    /** @var LoggerInterface */
    public $logger;

    /** @var ApiResponseInterface */
    private $apiResponse;

    public function __construct(BallRepositoryInterface $ballRepository, LoggerInterface $logger, ApiResponseInterface $apiResponse)
    {
        $this->ballRepository = $ballRepository;
        $this->logger = $logger;
        $this->apiResponse = $apiResponse;
    }

    /**
     * @Route("/ball/coordinates",methods={"GET"})
     */
    public function coordinates()
    {
        $ball = $this->ballRepository->get();

        return $this->apiResponse->generate([
            'x' => $ball->getCoordinates()->getX(),
            'y' => $ball->getCoordinates()->getY(),
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

        return $this->apiResponse->generate([
            'x' => $ball->getCoordinates()->getX(),
            'y' => $ball->getCoordinates()->getY(),
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

        return $this->apiResponse->generate([
            'x' => $ball->getCoordinates()->getX(),
            'y' => $ball->getCoordinates()->getY(),
        ]);
    }
}
