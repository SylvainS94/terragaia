<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="app_service_index", methods={"GET"})
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_service_new", methods={"GET", "POST"})
     */
    public function new(Request $request,EntityManagerInterface $entityManager, ServiceRepository $serviceRepository): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            //debut test

           $file = $form->get('picture')->getData();
            
// if (isset($file) === true) = Si un fichier est uploadé (depuis le formulaire)
        if($file) {
    // On reconstruit le nom du fichier pour le sécuriser
    
    // 1ere Etape = on deconstruit le nom du fichier et on variabilise.
        $extension = '.' . $file->guessExtension();  // on rajoute '.' pour inserer avec extension jpg = .jpg
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    
    // Assainissement du nom de fichier(filename)
    //$safeFilename = $slugger->slug($originalFilename); // Pas d'espace ou d'accents (remplacé par des -)
        $safeFilename = $service->getTitle(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
    // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
    // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
        $newFilename =  $safeFilename . '_' . uniqid() . $extension; 

    // try/catch = PHP natif pour methodes avec throw (lancer)
    try { // Envoie
        // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
        // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
        $file->move($this->getParameter('uploads_dir'), $newFilename);

        // On set le NOM de la photo, pas le CHEMIN
        $service->setPicture($newFilename);

    } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
        
    } // END catch()
} // END if ($file)

$entityManager->persist($service);
$entityManager->flush();

// Ici on ajoute un message qu'on affichera en twig (dans templates)
$this->addFlash('success', 'Bravo, votre service est bien en ligne !');
           
           
           //fin du test
            $serviceRepository->add($service);
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_service_show", methods={"GET"})
     */
    public function show(Service $service): Response
    {
        return $this->render('service/show.service.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_service_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,EntityManagerInterface $entityManager, Service $service, ServiceRepository $serviceRepository): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
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
                    $safeFilename = $service->getTitle(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
                // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
                // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
                    $newFilename =  $safeFilename . '_' . uniqid() . $extension; 
            
                // try/catch = PHP natif pour methodes avec throw (lancer)
                try { // Envoie
                    // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
                    // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
                    $file->move($this->getParameter('uploads_dir'), $newFilename);
            
                    // On set le NOM de la photo, pas le CHEMIN
                    $service->setPicture($newFilename);
            
                } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
                    
                } // END catch()
            } // END if ($file)
            
            $entityManager->persist($service);
            $entityManager->flush();
            
            // Ici on ajoute un message qu'on affichera en twig (dans templates)
            $this->addFlash('success', 'Bravo, votre service est bien en ligne !');


            $serviceRepository->add($service);
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_service_delete", methods={"POST"})
     */
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $serviceRepository->remove($service);
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
