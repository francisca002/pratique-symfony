<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService 
{
    public function __construct(private SluggerInterface $slugger, private ParameterBagInterface $parameter){}

    public function uploadFile(UploadedFile $couvertureFile): string
    {
        $originalFilename = pathinfo($couvertureFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$couvertureFile->guessExtension();

        try {
            $couvertureFile->move(
                $this->parameter->get('couvertures_directory'),
                $newFilename
            );
            return $newFilename;
        } catch (FileException $e) {
            throw new Exception($e->getMessage());
        }
    }
}