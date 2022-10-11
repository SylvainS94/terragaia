<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Commentary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServicesController extends AbstractController
{


    // ----------------TEST 1 ----PAGES "PARTICULIERS" ET "ENTREPRISES"------AFFICHAGE DU "SERVICE"-------------------------------------------------------------------------------------------------

    /**
     * @Route("/{category_title}/{service_title}_{id}", name="app_show_service", methods={"GET"})
     */
    public function showService(Service $service, EntityManagerInterface $entityManager): Response
    {
        // $service = $entityManager->getRepository(Service::class)->findBy(['id'=> $service->getId()]);
        
        //   Test  20/05  Affichage des commentaires par service :  ci-dessous + 'commentaries'=> $commentaries,
        $commentaries = $entityManager->getRepository(Commentary::class)->findBy(['service'=> $service->getId()]);

        return $this->render('services/show_service.html.twig', [
            'service' => $service, 
            'commentaries'=> $commentaries, 
        ]);
        
    }

}