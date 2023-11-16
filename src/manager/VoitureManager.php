<?php

namespace App\manager;

use App\Entity\Voiture;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VoitureManager
{
    public function __construct(private FileService $fileService, private EntityManagerInterface $entityManager){}
    public function manageCar(Voiture $voiture, FormInterface $form)
    {
        /** @var UploadedFile $couvertureFile */
        $couvertureFile = $form->get('couverture')->getData();
        if ($couvertureFile) {
            $newFilename = $this->fileService->uploadFile($couvertureFile);
            $voiture->setCouverture($newFilename);
        }
        $this->entityManager->persist($voiture);
        $this->entityManager->flush();
    }

    public function removeCar(Voiture $voiture)
    {
        $this->entityManager->remove($voiture);
        $this->entityManager->flush();
    }
}