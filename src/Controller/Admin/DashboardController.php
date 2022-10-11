<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Bill;
use App\Entity\User;
use App\Entity\Theme;
use App\Entity\Booking;
use App\Entity\Contact;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Commentary;
use App\Entity\Disponibility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin/dashboard")
 */
class DashboardController extends AbstractController
{    
    
    /**
     * @Route("/tableau-de-bord", name="show_dashboard", methods={"GET"})
     * // IsGranted("ROLE_ADMIN") ici cest une autre solution que denyAccessUnlessGranted dans fonction showDashboard ou que modifier le yaml security
     */
    public function showDashboard(EntityManagerInterface $entityManager): Response 
    {
        // try/catch fait parti de PHP nativement, il permet de gérer les class Exception (erreur).
        // On se sert d'un try/catch lorqu'on utilise des méthodes (fonctions) QUI LANCENT (throw) une Exception
        // Si la méthode lance l'erreur pendant son éxécution, alors l'Exception sera 'attrapéé' (catch).
        // Le code dans les accolades du catch sera alors éxécuté.
        try{
           $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        catch(AccessDeniedException $exception) {
           $this->addFlash('warning', 'Cette partie du site est réservée.');
          return $this->redirectToRoute('default_home');
        }
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $services = $entityManager->getRepository(Service::class)->findAll();
        $themes =$entityManager->getRepository(Theme::class)->findAll();
        $users =$entityManager->getRepository(User::class)->findAll();
        $bookings =$entityManager->getRepository(Booking::class)->findAll();
        $disponibilities =$entityManager->getRepository(Disponibility::class)->findAll();
        $bills =$entityManager->getRepository(Bill::class)->findAll();
        $contacts =$entityManager->getRepository(Contact::class)->findAll();
        $commentaries =$entityManager->getRepository(Commentary::class)->findAll();
        
        $totalCategories = count($categories);
        $totalServices = count($services);
        $totalThemes = count($themes);
        $totalUsers = count($users);
        $totalBookings = count($bookings);
        $totalDisponibilities = count($disponibilities);
        $totalBills = count($bills);
        $totalContacts = count($contacts);
        $totalCommentaries = count($commentaries);
        
        
        
        return $this->render('admin/show_dashboard.html.twig', [
            'categories' => $categories,
            'services'=> $services,
            'themes'=> $themes,
            'users' => $users,
            'bookings' => $bookings,
            'disponibilities' => $disponibilities,
            'bills'=> $bills,
            'contacts'=> $contacts,
            'commentaries'=> $commentaries,

            'totalCategories' => $totalCategories,
            'totalServices' => $totalServices,
            'totalThemes' => $totalThemes,
            'totalUsers' => $totalUsers,
            'totalBookings' => $totalBookings,
            'totalDisponibilities' => $totalDisponibilities,
            'totalBills' => $totalBills,
            'totalContacts' => $totalContacts,
            'totalCommentaries' => $totalCommentaries,
            
            
        ]); // redirection dans templates (voir en bas)
    }

}