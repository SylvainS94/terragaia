<?php

namespace App\Controller;

use App\Entity\Privacy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PrivaciesController extends AbstractController
{
    /**
     * @Route("/privacies", name="app_privacies")
     */
    public function privacies(EntityManagerInterface $entityManager): Response
    {
        $privacies = $entityManager->getRepository(Privacy::class)->findAll();
        return $this->render('privacies/show_privacies.html.twig', [
            'privacies' => $privacies,
        ]);
    }
                            
}