<?php

namespace App\Controller;

use App\Entity\Legal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalsController extends AbstractController
{
    /**
     * @Route("/legals", name="app_legals")
     */
    public function legals(EntityManagerInterface $entityManager): Response
    {
        $legals = $entityManager->getRepository(Legal::class)->findAll();
        return $this->render('legals/show_legals.html.twig', [
            'legals' => $legals,
        ]);
    }
                            
}