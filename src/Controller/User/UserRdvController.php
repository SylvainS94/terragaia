<?php

namespace App\Controller\User;

use App\Entity\Booking;
use App\Entity\Disponibility;
use App\Form\BookingType;
use App\Form\CalendFormType;
use App\Repository\DisponibilityRepository;
use App\Services\SearchCalendar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserRdvController extends AbstractController
{
    /**
     * @Route("/user/rdv", name="app_user_rdv")
     */
    public function index(Request $request, DisponibilityRepository $disponibilityRepository): Response
    {
        $searchCalendar = new SearchCalendar();
        $form =$this->createForm(CalendFormType::class, $searchCalendar);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $selectedDate = $form->get('dateAppointment')->getData();

            $disponibilities = $disponibilityRepository->findBy([
                'date_slot' => $selectedDate->format('d/m/Y'),
                'dispo' => false
            ]);
            
            return $this->render('user_rdv/disponibilities.html.twig', [
                'slots' => $disponibilities
            ]);
        }

        return $this->render('user_rdv/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/reservation/{id}", name="app_user_reservation")
     */
    public function reservation($id, EntityManagerInterface $manager, Request $request, DisponibilityRepository $disponibilityRepository): Response
    {
        $booking = new Booking();
        $user = $this->getUser();
        $disponibility = $disponibilityRepository->find($id);

        $form =$this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $booking->setUser($user);
            $booking->setDisponibility($disponibility);
            
            $manager->persist($booking);

            $disponibility->setDispo(true);

            $manager->persist($disponibility);
            $manager->flush();
            $this->addFlash('success', "Vous avez réservez votre RDV/Séance, N° Identification : <strong>". $booking->getId() ." </strong> avec succès !");
            // TODO ici faire envoi de l'email a l'user pour confirmer rdv                                    A FAIRE
            return $this->redirectToRoute('app_user_show_rdvs');
        }
        return $this->renderForm('user_rdv/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);

    }

}
