<?php

namespace App\Controller\Admin;

use App\Entity\Privacy;
use App\Form\PrivacyType;
use App\Repository\PrivacyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/privacy")
 */
class PrivacyController extends AbstractController
{
    /**
     * @Route("/", name="app_privacy_index", methods={"GET"})
     */
    public function index(PrivacyRepository $privacyRepository): Response
    {
        return $this->render('privacy/index.html.twig', [
            'privacies' => $privacyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_privacy_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PrivacyRepository $privacyRepository): Response
    {
        $privacy = new Privacy();
        $form = $this->createForm(PrivacyType::class, $privacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $privacyRepository->add($privacy);
            return $this->redirectToRoute('app_privacy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('privacy/new.html.twig', [
            'privacy' => $privacy,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_privacy_show", methods={"GET"})
     */
    public function show(Privacy $privacy): Response
    {
        return $this->render('privacy/show.html.twig', [
            'privacy' => $privacy,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_privacy_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Privacy $privacy, PrivacyRepository $privacyRepository): Response
    {
        $form = $this->createForm(PrivacyType::class, $privacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $privacyRepository->add($privacy);
            return $this->redirectToRoute('app_privacy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('privacy/edit.html.twig', [
            'privacy' => $privacy,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_privacy_delete", methods={"POST"})
     */
    public function delete(Request $request, Privacy $privacy, PrivacyRepository $privacyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$privacy->getId(), $request->request->get('_token'))) {
            $privacyRepository->remove($privacy);
        }

        return $this->redirectToRoute('app_privacy_index', [], Response::HTTP_SEE_OTHER);
    }
}
