<?php

namespace App\Presentation\Web;

use App\Core\Component\Stadium\Application\Repository\StadiumRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StadiumController extends Controller
{
    /** @var StadiumRepositoryInterface */
    private $stadiumRepository;

    public function __construct(StadiumRepositoryInterface $stadiumRepository, LoggerInterface $logger)
    {
        $this->stadiumRepository = $stadiumRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/")
     * @Route("/stadium")
     */
    public function default()
    {
        $stadium = $this->stadiumRepository->get();

        return $this->render('stadium.html.twig', [
            'dimension' => $stadium->getDimension(),
        ]);
    }

    /**
     * @Route("/stadium/dimension")
     */
    public function getDimension()
    {
        $stadium = $this->stadiumRepository->get();

        return new JsonResponse([
            'dimension' => $stadium->getDimension(),
        ]);
    }

    /**
     * @Route("/stadium/distributePlayer/{team}", methods={"DELETE"})
     */
    public function distributePlayer($team)
    {
        $stadium = $this->stadiumRepository->get();
        $newName = $stadium->distributePlayer($team);
        $this->stadiumRepository->update($stadium);

        return new JsonResponse(['name' => $newName]);
    }
}
