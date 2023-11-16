<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/person', name: 'person_')]
class PersonController extends AbstractController
{
    public function __construct(private PersonRepository $personRepository) {}

    #[Route('/', name: 'list', methods: ['GET'])]
    public function indexAction(): Response
    {
        $persons = $this->personRepository->findAll();
        return $this->render('person/index.html.twig', [
            'persons' => $persons
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function addPerson(Request $request, EntityManagerInterface $entityManager): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('person_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Person $person): Response
    {
        return $this->render('person/show.html.twig',[
            'person' => $person
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Person $person, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('person_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function deletePerson(Person $person, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($person);
        $entityManager->flush();

        return $this->redirectToRoute('person_list', [], Response::HTTP_SEE_OTHER);
    }
}