<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Form\ContactCheckType;
use Doctrine\ORM\EntityManagerInterface;


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

        // Serialize contacts to JSON format
        $jsonContent = $serializer->serialize($contacts, 'json', [
            'attributes' => [
                'id',
                'name',
                'phoneList',
                'birthday' => ['format' => 'd/m/Y']
            ]
        ]);
    
        return new JsonResponse($jsonContent, 200, [], true);
    }
}


