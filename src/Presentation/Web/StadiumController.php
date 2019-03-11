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
            'surface' => $stadium->getSurface(),
        ]);
    }

    /**
     * @Route("/stadium/surface")
     */
    public function getSurface()
    {
        $stadium = $this->stadiumRepository->get();

        return new JsonResponse([
            'surface' => $stadium->getSurface(),
        ]);
    }

    /**
     * @Route("/stadium/distributePlayer/{team}", methods={"GET"})
     */
    public function distributePlayer($team)
    {
        $stadium = $this->stadiumRepository->get();
        list($name,$icon) = $stadium->distributePlayer($team);
        $this->stadiumRepository->update($stadium);

        return new JsonResponse(['name' => $name,'icon' => $icon]);
    }
}
