<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function home(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }



//-----------------------------------------------TEST 1----AFFICHAGE SERVICES DANS NAV------------------------------------

    /**
    * @Route("/category", name="app_render_categories_in_nav", methods={"GET"})
    */
    public function renderCategoriesInNav(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('rendered/nav_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    
}
