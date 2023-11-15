<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'color' => 'white',
            'nombre' => [4,5,9]
        ]);
    }

    #[Route('/page2', name: 'app_page2')]
    public function index2(): Response
    {
        return $this->render('page/index2.html.twig', [
            'color' => 'white',
            'nombre' => [4,5,9]
        ]);
    }

    #[Route('/api', name: 'api')]
    public function toApi(): JsonResponse 
    {
        return $this->json([
            'nom' => 'Koto',
            'age' => 40
        ]);
    }
}
