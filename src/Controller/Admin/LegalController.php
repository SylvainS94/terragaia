<?php

namespace App\Controller\Admin;

use App\Entity\Legal;
use App\Form\LegalType;
use App\Repository\LegalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/legal")
 */
class LegalController extends AbstractController
{
    /**
     * @Route("/", name="app_legal_index", methods={"GET"})
     */
    public function index(LegalRepository $legalRepository): Response
    {
        return $this->render('legal/index.html.twig', [
            'legals' => $legalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_legal_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LegalRepository $legalRepository): Response
    {
        $legal = new Legal();
        $form = $this->createForm(LegalType::class, $legal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $legalRepository->add($legal);
            return $this->redirectToRoute('app_legal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('legal/new.html.twig', [
            'legal' => $legal,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_legal_show", methods={"GET"})
     */
    public function show(Legal $legal): Response
    {
        return $this->render('legal/show.html.twig', [
            'legal' => $legal,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_legal_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Legal $legal, LegalRepository $legalRepository): Response
    {
        $form = $this->createForm(LegalType::class, $legal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $legalRepository->add($legal);
            return $this->redirectToRoute('app_legal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('legal/edit.html.twig', [
            'legal' => $legal,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_legal_delete", methods={"POST"})
     */
    public function delete(Request $request, Legal $legal, LegalRepository $legalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$legal->getId(), $request->request->get('_token'))) {
            $legalRepository->remove($legal);
        }

        return $this->redirectToRoute('app_legal_index', [], Response::HTTP_SEE_OTHER);
    }
}
