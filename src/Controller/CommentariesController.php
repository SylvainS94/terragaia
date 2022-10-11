<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Service;
use App\Form\CommentaryFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentariesController extends AbstractController
{
    /**
     * @Route("ajouter-un-commentaire?/{category_title}/{service_title}_{id}", name="app_add_commentary", methods={"GET|POST"})
     */
    public function addCommentary(Service $service, Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentary= new Commentary();

        $form =$this->createForm(CommentaryFormType::class, $commentary)->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid() === false ) {
            $this->addFlash('warning', 'Votre commentaire est vide !');

            return $this->redirectToRoute('app_show_service', [
                'category_title' => $service->getCategory()->getTitle(),
                'service_title' => $service->getTitle(),
                'id' => $service->getId()
            ]);
        } // endif

        if($form->isSubmitted() && $form->isValid()) { // === true
            
            $commentary->setService($service);
            $commentary->setCreatedAt(new DateTime());
            $commentary->setupdatedAt(new DateTime());

            $commentary->setAuthor($this->getUser());

            $entityManager->persist($commentary);
            $entityManager->flush();

            $this->addFlash('success', "Vous avez commenté le service <strong>". $service->getTitle() ." </strong> avec succès !");

            return $this->redirectToRoute('app_show_service', [
                'category_title' => $service->getCategory()->getTitle(),
                'service_title' => $service->getTitle(),
                'id' => $service->getId()
            ]);
        } // endif

        return $this->render('commentaries/new.html.twig', [
            // 'commentary' => $commentary,
            'form' => $form->createView()
        ]);

    } // End function addCommentary



} // End class CommentaryController
