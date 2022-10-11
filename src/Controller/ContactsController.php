<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Form\UserContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
    * @Route("/contacts")
    */
    class ContactsController extends AbstractController
{
    
    /**
     * @Route("/new", name="app_new_contact", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(UserContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $contact->setCreatedAt(new DateTime());
            $contact->setupdatedAt(new DateTime());

            $contact->setUser($this->getUser());

            $entityManager->persist($contact);
            $entityManager->flush();
            
            $contactRepository->add($contact);

            $this->addFlash('success', "<strong> Vous avez envoyé un message avec succès </strong>");

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contacts/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }


}