<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Commentary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryServicesController extends AbstractController
{

// ----------------TEST 1 ----PAGES "PARTICULIERS" ET "ENTREPRISES"------AFFICHAGE DES SERVICES DE LA CATEGORIE"-------------------------------------------------------------------------------------------------
    
    /**
     * @Route("/{category_title}_{id}", name="app_show_services_from_category", methods={"GET"})
     */
    public function showServicesFromCategory(Category $category, EntityManagerInterface $entityManager) : Response
    {
        $services = $entityManager->getRepository(Service::class)->findBy(['category'=> $category->getId()]); 

        return $this->render('services/show_services_from_category.html.twig', [
            'services' => $services,
            'category' =>$category
        ]);

    }


}