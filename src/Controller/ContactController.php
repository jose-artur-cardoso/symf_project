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
        
        $contacts = $contactRepository->findAllWithPhones();

        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact, [
            'action' => $this->generateUrl('app_contact_create'),
            'method' => 'POST',
        ]);

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contact/create", name="app_contact_create")
     */
    public function createContact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        
        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            foreach ($contact->getPhones() as $phone) {
                $entityManager->persist($phone);
            }
            $contact = $form->getData();
            $entityManager->persist($contact);
            $entityManager->flush();

            flash()->addSuccess('The contact was inserted with success.');
        
            return $this->redirectToRoute('app_contact');
        } else {
            flash()->addError('There was an error.');
        }

        
        return $this->renderForm('contact/new.html.twig', [
            'form' => $form,
        ]);

    }
}
