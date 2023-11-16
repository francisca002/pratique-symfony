<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\manager\VoitureManager;
use App\Repository\VoitureRepository;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/voiture')]
class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_voiture_index', methods: ['GET'])]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }

    #[Route('/manage/{id?}', name: 'app_voiture_manage', methods: ['GET', 'POST'])]
    public function manage(Request $request, ?Voiture $voiture, VoitureManager $voitureManager): Response
    {
        $voiture = $voiture ?? new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $voitureManager->manageCar($voiture, $form);
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voiture/manage.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voiture_show', methods: ['GET'])]
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_voiture_delete', methods: ['GET', 'POST'])]
    public function delete(VoitureManager $voitureManager, Voiture $voiture): Response
    {
        $voitureManager->removeCar($voiture);

        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
}
