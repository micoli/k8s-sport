<?php

namespace App\Presentation\Web;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends Controller
{
    /**
     * @Route("/healthz",methods={"GET"})
     */
    public function position()
    {
        return new JsonResponse([
            'status' => true,
        ]);
    }
}
