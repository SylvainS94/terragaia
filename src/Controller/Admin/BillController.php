<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Bill;
use App\Form\BillType;
use App\Repository\BillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin/bill")
 */
class BillController extends AbstractController
{
    /**
     * @Route("/", name="app_bill_index", methods={"GET"})
     */
    public function index(BillRepository $billRepository): Response
    {
        return $this->render('bill/index.html.twig', [
            'bills' => $billRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_bill_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, BillRepository $billRepository): Response
    {
        $bill = new Bill();
        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $file = $form->get('content')->getData();
            
            // if (isset($file) === true) = Si un fichier est uploadé (depuis le formulaire)
                    if($file) {
                // On reconstruit le nom du fichier pour le sécuriser
                
                // 1ere Etape = on deconstruit le nom du fichier et on variabilise.
                    $extension = '.' . $file->guessExtension();  // on rajoute '.' pour inserer avec extension jpg = .jpg
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                // Assainissement du nom de fichier(filename)
                //$safeFilename = $slugger->slug($originalFilename); // Pas d'espace ou d'accents (remplacé par des -)
                    $safeFilename = $bill->getId(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
                // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
                // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
                    $newFilename =  $safeFilename . '_' . uniqid() . $extension; 
            
                // try/catch = PHP natif pour methodes avec throw (lancer)
                try { // Envoie
                    // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
                    // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
                    $file->move($this->getParameter('uploads_dir'), $newFilename);
            
                    // On set le NOM de la photo, pas le CHEMIN
                    $bill->setContent($newFilename);
            
                } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
                    
                } // END catch()
            } // END if ($file)
            
            $bill->setCreatedAt(new DateTime());
            $bill->setupdatedAt(new DateTime());

            $entityManager->persist($bill);
            $entityManager->flush();
            
            // Ici on ajoute un message qu'on affichera en twig (dans templates)
            $this->addFlash('success', 'Bravo, votre devis est bien en ligne !');


            $billRepository->add($bill);
            return $this->redirectToRoute('app_bill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bill/new.html.twig', [
            'bill' => $bill,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_bill_show", methods={"GET"})
     */
    public function show(Bill $bill): Response
    {
        return $this->render('bill/show.html.twig', [
            'bill' => $bill,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_bill_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Bill $bill,EntityManagerInterface $entityManager, BillRepository $billRepository): Response
    {
        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('content')->getData();
            
            // if (isset($file) === true) = Si un fichier est uploadé (depuis le formulaire)
                    if($file) {
                // On reconstruit le nom du fichier pour le sécuriser
                
                // 1ere Etape = on deconstruit le nom du fichier et on variabilise.
                    $extension = '.' . $file->guessExtension();  // on rajoute '.' pour inserer avec extension jpg = .jpg
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                // Assainissement du nom de fichier(filename)
                //$safeFilename = $slugger->slug($originalFilename); // Pas d'espace ou d'accents (remplacé par des -)
                    $safeFilename = $bill->getId(); // on choisi d'indiquer les alias et pas l'originalFilename (choix perso)
                // 2eme Etape : On reconstruit le nom du fichier maintenant qu'il est safe.
                // uniqid cree une id unique pour chaque photo + option en arg (prefix:"", more_entropy:true) = rajouter un second id en +
                    $newFilename =  $safeFilename . '_' . uniqid() . $extension; 
            
                // try/catch = PHP natif pour methodes avec throw (lancer)
                try { // Envoie
                    // On a configuré avant un parametre 'uploads_dir' dans services.yaml (dans config/routes)
                    // Ce parametre contient le chemin absolu de notre dossier d'upload de photo
                    $file->move($this->getParameter('uploads_dir'), $newFilename);
            
                    // On set le NOM de la photo, pas le CHEMIN
                    $bill->setContent($newFilename);
            
                } catch (FileException $exception) { // si erreur de type , rattrape FileException avec un objet $exception et n'affiche pas l'erreur
                    
                } // END catch()
            } // END if ($file)

            $bill->setupdatedAt(new DateTime());

            $entityManager->persist($bill);
            $entityManager->flush();
            
            // Ici on ajoute un message qu'on affichera en twig (dans templates)
            $this->addFlash('success', 'Bravo, votre devis est bien en ligne !');

            $billRepository->add($bill);
            return $this->redirectToRoute('app_bill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bill/edit.html.twig', [
            'bill' => $bill,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_bill_delete", methods={"POST"})
     */
    public function delete(Request $request, Bill $bill, BillRepository $billRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bill->getId(), $request->request->get('_token'))) {
            $billRepository->remove($bill);
        }

        return $this->redirectToRoute('app_bill_index', [], Response::HTTP_SEE_OTHER);
    }
}
