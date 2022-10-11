<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request,EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//ajout test-------------
        $file = $form->get('picture')->getData();
            
// if (isset($file) === true) = Si un fichier est uploadé (depuis le formulaire)
        if($file) {
    // On reconstruit le nom du fichier pour le sécuriser
    
    // 1ere Etape = on deconstruit le nom du fichier et on variabilise.
        $extension = '.' . $file->guessExtension();  // on rajoute '.' pour inserer avec extension jpg = .jpg
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    
    // Assainissement du nom de fichier(filename)
    //$safeFilename = $slugger->slug($originalFilename); // Pas d'espace ou d'accents (remplacé par des -)
        $safeFilename = $category->getTitle(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
    // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
    // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
        $newFilename =  $safeFilename . '_' . uniqid() . $extension; 

    // try/catch = PHP natif pour methodes avec throw (lancer)
    try { // Envoie
        // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
        // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
        $file->move($this->getParameter('uploads_dir'), $newFilename);

        // On set le NOM de la photo, pas le CHEMIN
        $category->setPicture($newFilename);

    } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
        
    } // END catch()
} // END if ($file)

$entityManager->persist($category);
$entityManager->flush();

// Ici on ajoute un message qu'on affichera en twig (dans templates)
$this->addFlash('success', 'Bravo, votre catégorie est bien en ligne !');

            $categoryRepository->add($category);
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/voir-categorie/{id}", name="app_category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,EntityManagerInterface $entityManager, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //test en cours--------------------------------------------------------------------------------
            $file = $form->get('picture')->getData();
            
            // if (isset($file) === true) = Si un fichier est uploadé (depuis le formulaire)
                    if($file) {
                // On reconstruit le nom du fichier pour le sécuriser
                
                // 1ere Etape = on deconstruit le nom du fichier et on variabilise.
                    $extension = '.' . $file->guessExtension();  // on rajoute '.' pour inserer avec extension jpg = .jpg
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                // Assainissement du nom de fichier(filename)
                //$safeFilename = $slugger->slug($originalFilename); // Pas d'espace ou d'accents (remplacé par des -)
                    $safeFilename = $category->getTitle(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
                // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
                // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
                    $newFilename =  $safeFilename . '_' . uniqid() . $extension; 
            
                // try/catch = PHP natif pour methodes avec throw (lancer)
                try { // Envoie
                    // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
                    // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
                    $file->move($this->getParameter('uploads_dir'), $newFilename);
            
                    // On set le NOM de la photo, pas le CHEMIN
                    $category->setPicture($newFilename);
            
                } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
                    
                } // END catch()
            } // END if ($file)
            
            $entityManager->persist($category);
            $entityManager->flush();
            
            // Ici on ajoute un message qu'on affichera en twig (dans templates)
            $this->addFlash('success', 'Bravo, votre catégorie est bien en ligne !');

            $categoryRepository->add($category);
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category);
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
