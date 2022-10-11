<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Form\BookingAdminFormType;
use App\Form\BookingType;
use App\Form\CalendFormType;
use App\Services\SearchCalendar;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DisponibilityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="app_booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_booking_new", methods={"GET", "POST"})
     */
    public function new($id, Request $request, BookingRepository $bookingRepository, EntityManagerInterface $manager, DisponibilityRepository $disponibilityRepository): Response
    {
        $booking = new Booking();
        $disponibility = $disponibilityRepository->find($id);

        $form = $this->createForm(BookingAdminFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setDisponibility($disponibility);
            $manager->persist($booking);
            $disponibility->setDispo(true);
            $manager->persist($disponibility);
            $manager->flush();
            // $booking->setDisponibility($disponibility);
            // $manager->persist($booking);
            // $bookingRepository->add($booking);

            // $disponibility->setDispo(true);
            
            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_rdvs/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/rdvs", name="app_admin_rdvs")
     */
    public function rdvInfo(Request $request, DisponibilityRepository $disponibilityRepository): Response
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
            
            return $this->render('user_rdvs/disponibilities.html.twig', [
                'slots' => $disponibilities
            ]);
        }

        return $this->render('user_rdvs/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/{id}", name="app_booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_booking_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking);
            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_booking_delete", methods={"POST"})
     */
    public function delete(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $bookingRepository->remove($booking);
        }

        return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
    }
}
