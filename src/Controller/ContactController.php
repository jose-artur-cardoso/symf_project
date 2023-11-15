<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Form\ContactCheckType;
use Doctrine\ORM\EntityManagerInterface;


class ContactController extends AbstractController
{

    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(): Response
    {
        
        $contacts = $this->contactRepository->findAll();

        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact, [
            'action' => $this->generateUrl('app_contact_create'),
            'method' => 'POST',
        ]);

        $formCheckBox = $this->createForm(ContactCheckType::class, null, [
            'action' => $this->generateUrl('app_contact_delete'),
            'method' => 'POST',
        ]);

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
            'form' => $form->createView(),
            'formCheckBox' => $formCheckBox->createView(),
            // 'delForm' => $delForm->createView(),
        ]);
    }

    /**
     * @Route("/contact/create", name="app_contact_create")
     */
    public function createContact(Request $request, EntityManagerInterface $entityManager): Response
    {
        try{
            $openModal = false;
            $contact = new Contact();
            $form = $this->createForm(ContactFormType::class, $contact);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                
                $contact = $form->getData();
                $entityManager->persist($contact);
                $entityManager->flush();
    
                flash()->addSuccess('The contact was inserted with success.');
                return $this->redirectToRoute('app_contact');
    
            }
        }
        catch(\Exception $e){
            flash()->addError('There was an error.'); 
            $openModal = true;
        }
        
        $contacts = $this->contactRepository->findAll();

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
            'form' => $form->createView(),
            'openModal' => $openModal,
        ]);

    }

    /**
    * @Route("/contact/delete", name="app_contact_delete")
    */
    public function deleteContacts(Request $request, EntityManagerInterface $entityManager)
    {
        try{
            $selectedEntityIds = $request->get('selected_contacts');
    
            foreach ($selectedEntityIds as $entityId) {
                $entity = $entityManager->getRepository(Contact::class)->find($entityId);
                if ($entity) {
                    $entityManager->remove($entity);
                }
            }
            $entityManager->flush();
            flash()->addSuccess('Contacts removed with success');
        }
        catch(\Exception $e){
            flash()->addError('An error has occurred during the deletion of the contacts.');
        }

        return $this->redirectToRoute('app_contact');
        
    }
}

