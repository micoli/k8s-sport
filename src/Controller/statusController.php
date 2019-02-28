<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatusController extends Controller
{
    /**
     * @Route("/")
     */
    public function default()
    {
        return new JsonResponse(['test'=>1]);
    }
}