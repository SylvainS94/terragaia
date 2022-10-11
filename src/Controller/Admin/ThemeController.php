<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin/theme")
 */
class ThemeController extends AbstractController
{
    /**
     * @Route("/", name="app_theme_index", methods={"GET"})
     */
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('theme/index.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_theme_new", methods={"GET", "POST"})
     */
    public function new(Request $request,EntityManagerInterface $entityManager, ThemeRepository $themeRepository): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('picture')->getData();
            
            // if (isset($file) === true) = Si un fichier est uploadé (depuis le formulaire)
                    if($file) {
                // On reconstruit le nom du fichier pour le sécuriser
                
                // 1ere Etape = on deconstruit le nom du fichier et on variabilise.
                    $extension = '.' . $file->guessExtension();  // on rajoute '.' pour inserer avec extension jpg = .jpg
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                // Assainissement du nom de fichier(filename)
                //$safeFilename = $slugger->slug($originalFilename); // Pas d'espace ou d'accents (remplacé par des -)
                    $safeFilename = $theme->getTitle(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
                // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
                // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
                    $newFilename =  $safeFilename . '_' . uniqid() . $extension; 
            
                // try/catch = PHP natif pour methodes avec throw (lancer)
                try { // Envoie
                    // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
                    // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
                    $file->move($this->getParameter('uploads_dir'), $newFilename);
            
                    // On set le NOM de la photo, pas le CHEMIN
                    $theme->setPicture($newFilename);
            
                } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
                    
                } // END catch()
            } // END if ($file)
            $entityManager->persist($theme);
            $entityManager->flush();

// Ici on ajoute un message qu'on affichera en twig (dans templates)
            $this->addFlash('success', 'Bravo, votre thème est bien en ligne !');
            
            $themeRepository->add($theme);
            return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('theme/new.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_theme_show", methods={"GET"})
     */
    public function show(Theme $theme): Response
    {
        return $this->render('theme/show.html.twig', [
            'theme' => $theme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_theme_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,EntityManagerInterface $entityManager, Theme $theme, ThemeRepository $themeRepository): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            
            // if (isset($file) === true) = Si un fichier est uploadé (depuis le formulaire)
                    if($file) {
                // On reconstruit le nom du fichier pour le sécuriser
                
                // 1ere Etape = on deconstruit le nom du fichier et on variabilise.
                    $extension = '.' . $file->guessExtension();  // on rajoute '.' pour inserer avec extension jpg = .jpg
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                // Assainissement du nom de fichier(filename)
                //$safeFilename = $slugger->slug($originalFilename); // Pas d'espace ou d'accents (remplacé par des -)
                    $safeFilename = $theme->getTitle(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
                // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
                // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
                    $newFilename =  $safeFilename . '_' . uniqid() . $extension; 
            
                // try/catch = PHP natif pour methodes avec throw (lancer)
                try { // Envoie
                    // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
                    // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
                    $file->move($this->getParameter('uploads_dir'), $newFilename);
            
                    // On set le NOM de la photo, pas le CHEMIN
                    $theme->setPicture($newFilename);
            
                } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
                    
                } // END catch()
            } // END if ($file)
            $entityManager->persist($theme);
            $entityManager->flush();

// Ici on ajoute un message qu'on affichera en twig (dans templates)
            $this->addFlash('success', 'Bravo, votre thème est bien en ligne !');
            
            $themeRepository->add($theme);
            return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('theme/edit.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_theme_delete", methods={"POST"})
     */
    public function delete(Request $request, Theme $theme, ThemeRepository $themeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $themeRepository->remove($theme);
        }

        return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
    }
}
