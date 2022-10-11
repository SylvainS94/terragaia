<?php

namespace App\Controller\Admin;

use App\Entity\Coordonate;
use App\Form\CoordonateType;
use App\Repository\CoordonateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/coordonate")
 */
class CoordonateController extends AbstractController
{
    /**
     * @Route("/", name="app_coordonate_index", methods={"GET"})
     */
    public function index(CoordonateRepository $coordonateRepository): Response
    {
        return $this->render('coordonate/index.html.twig', [
            'coordonates' => $coordonateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_coordonate_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CoordonateRepository $coordonateRepository): Response
    {
        $coordonate = new Coordonate();
        $form = $this->createForm(CoordonateType::class, $coordonate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coordonateRepository->add($coordonate);
            return $this->redirectToRoute('app_coordonate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coordonate/new.html.twig', [
            'coordonate' => $coordonate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_coordonate_show", methods={"GET"})
     */
    public function show(Coordonate $coordonate): Response
    {
        return $this->render('coordonate/show.html.twig', [
            'coordonate' => $coordonate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_coordonate_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Coordonate $coordonate, CoordonateRepository $coordonateRepository): Response
    {
        $form = $this->createForm(CoordonateType::class, $coordonate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coordonateRepository->add($coordonate);
            return $this->redirectToRoute('app_coordonate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coordonate/edit.html.twig', [
            'coordonate' => $coordonate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_coordonate_delete", methods={"POST"})
     */
    public function delete(Request $request, Coordonate $coordonate, CoordonateRepository $coordonateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coordonate->getId(), $request->request->get('_token'))) {
            $coordonateRepository->remove($coordonate);
        }

        return $this->redirectToRoute('app_coordonate_index', [], Response::HTTP_SEE_OTHER);
    }
}
