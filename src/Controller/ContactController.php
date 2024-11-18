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
use App\Entity\Phone;


class ContactController extends AbstractController
{

    /**
     * @Route("/", name="app_contact", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository, Request $request): Response
    {

        $template = $request->query->get('ajax') ? '_list.html.twig' : 'index.html.twig';
        return $this->render('contact/'. $template, [
            'contacts' => $contactRepository->findAll(),
        ]);
    }
   
    /**
     * @Route("contact/new", name="app_contact_create", methods={"GET", "POST"})
     */
    public function new(Request $request, ContactRepository $contactRepository): Response
    {
        
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $contactRepository->add($contact, true);

            return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
        }

        $template = $request->isXmlHttpRequest() ? '_form.html.twig' : '_form.html.twig';

        return $this->renderForm('contact/'. $template, [
            'contact' => $contact,
            'form' => $form,
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

