<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;


class ApiController extends AbstractController
{
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/api", name="api")
     */
    public function getContacts(SerializerInterface $serializer): JsonResponse
    {
        $contacts = $this->contactRepository->findAll();

        $context = [
            DateTimeNormalizer::FORMAT_KEY => 'd/m/Y',  // Define the date format globally for DateTime fields
        ];
        
        // dd($contacts);
        // Serialize contacts to JSON format
        $jsonContent = $serializer->serialize($contacts, 'json', [
            'attributes' => [
                'id',
                'name',
                'phones',
                'birthday' => ['format' => 'd/m/Y'],
                'phones'
            ]
        ] + $context );
    
        return new JsonResponse($jsonContent, 200, [], true);
    }
}


