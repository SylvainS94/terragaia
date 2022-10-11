<?php

namespace App\Controller;

use App\Entity\Coordonate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CoordonatesController extends AbstractController
{
    /**
     * @Route("/coordonates", name="app_coordonates")
     */
    public function coordonates(EntityManagerInterface $entityManager): Response
    {
        $coordonates = $entityManager->getRepository(Coordonate::class)->findAll();
        return $this->render('coordonates/show_coordonates.html.twig', [
            'coordonates' => $coordonates,
        ]);
    }
                            
}
