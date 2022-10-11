<?php

namespace App\Controller\Admin;

use App\Entity\Disponibility;
use App\Form\DisponibilityType;
use App\Repository\DisponibilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/disponibility")
 */
class DisponibilityController extends AbstractController
{
    /**
     * @Route("/", name="app_disponibility_index", methods={"GET"})
     */
    public function index(DisponibilityRepository $disponibilityRepository): Response
    {
        return $this->render('disponibility/index.html.twig', [
            'disponibilities' => $disponibilityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_disponibility_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DisponibilityRepository $disponibilityRepository): Response
    {
        $disponibility = new Disponibility();
        $form = $this->createForm(DisponibilityType::class, $disponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedDate = $form->get('date_slot')->getData();
            // $string = $selectedDate->format('Y/m/d');
            $string = $selectedDate->format('d/m/Y');
            $disponibility->setDateSlot($string);
            $disponibilityRepository->add($disponibility);
            
            return $this->redirectToRoute('app_disponibility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disponibility/new.html.twig', [
            'disponibility' => $disponibility,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_disponibility_show", methods={"GET"})
     */
    public function show(Disponibility $disponibility): Response
    {
        return $this->render('disponibility/show.html.twig', [
            'disponibility' => $disponibility,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_disponibility_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Disponibility $disponibility, DisponibilityRepository $disponibilityRepository): Response
    {
        $form = $this->createForm(DisponibilityType::class, $disponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $disponibilityRepository->add($disponibility);
            return $this->redirectToRoute('app_disponibility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disponibility/edit.html.twig', [
            'disponibility' => $disponibility,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_disponibility_delete", methods={"POST"})
     */
    public function delete(Request $request, Disponibility $disponibility, DisponibilityRepository $disponibilityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$disponibility->getId(), $request->request->get('_token'))) {
            $disponibilityRepository->remove($disponibility);
        }

        return $this->redirectToRoute('app_disponibility_index', [], Response::HTTP_SEE_OTHER);
    }
}
