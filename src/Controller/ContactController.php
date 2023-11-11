<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(ContactRepository $contactRepository): Response
    {

        $contacts = $contactRepository->findAll();

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/contact/new", name="app_contact_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        
        $form = $this->createForm(ContactFormType::class, $contact);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $contact = $form->getData();
            
            $entityManager->persist($contact);

            $entityManager->flush();

            return $this->redirectToRoute('contact_success');
        }

        
        return $this->renderForm('contact/new.html.twig', [
            'form' => $form,
        ]);

    }
}
