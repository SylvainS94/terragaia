<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Commentary;
use App\Entity\Service;
use App\Form\CommentaryType;
use App\Repository\CommentaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/commentary")
 */
class CommentaryController extends AbstractController
{
    /**
     * @Route("/", name="app_commentary_index", methods={"GET"})
     */
    public function index(CommentaryRepository $commentaryRepository): Response
    {
        return $this->render('commentary/index.html.twig', [
            'commentaries' => $commentaryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_commentary_new", methods={"GET", "POST"})
     */
    public function new(Request $request,Service $service, EntityManagerInterface $entityManager, CommentaryRepository $commentaryRepository): Response
    {
        $commentary = new Commentary();
        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentary->setService($service);
            $commentary->setCreatedAt(new DateTime());
            $commentary->setupdatedAt(new DateTime());

            $entityManager->persist($commentary);
            $entityManager->flush();

            $commentaryRepository->add($commentary);
            
            return $this->redirectToRoute('app_commentary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentary/new.html.twig', [
            'commentary' => $commentary,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_commentary_show", methods={"GET"})
     */
    public function show(Commentary $commentary): Response
    {
        return $this->render('commentary/show.html.twig', [
            'commentary' => $commentary,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_commentary_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,Service $service, Commentary $commentary,EntityManagerInterface $entityManager, CommentaryRepository $commentaryRepository): Response
    {
        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentary->setService($service);
            $commentary->setCreatedAt(new DateTime());
            $commentary->setupdatedAt(new DateTime());

            $entityManager->persist($commentary);
            $entityManager->flush();

            $commentaryRepository->add($commentary);
            return $this->redirectToRoute('app_commentary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentary/edit.html.twig', [
            'commentary' => $commentary,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_commentary_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentary $commentary, CommentaryRepository $commentaryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentary->getId(), $request->request->get('_token'))) {
            $commentaryRepository->remove($commentary);
        }

        return $this->redirectToRoute('app_commentary_index', [], Response::HTTP_SEE_OTHER);
    }
}
