<?php

namespace App\Controller;

use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThemesController extends AbstractController
{
    /**
     * @Route("/themes", name="app_themes")
     */
    public function themes(EntityManagerInterface $entityManager): Response
    {
        $themes = $entityManager->getRepository(Theme::class)->findAll();
        return $this->render('themes/themes.html.twig', [
            'themes' => $themes,
        ]);
    }
                            
    


}
