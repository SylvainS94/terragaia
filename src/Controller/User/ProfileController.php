<?php

namespace App\Controller\User;

use DateTime;
use App\Entity\Bill;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Contact;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Commentary;
use App\Form\UserContactType;
use App\Form\CommentaryFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\BookingRepository;
use App\Repository\ContactRepository;
use App\Repository\CommentaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/user/profil")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="app_profile", methods={"GET"})
     */
    public function showUserProfile(): Response
    {
        return $this->render('profile/show_profile.html.twig');
    }

    /**
     * @Route("/voir-mon-profil/{id}", name="app_show_user_profil", methods={"GET"})
     */
    public function showUserProfil(User $user): Response
    {
        return $this->render('profile/show_user_profil.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/modifier-mon-profil/{id}", name="app_update_user", methods={"GET|POST"})
     */
    public function updateUser(User $user, Request $request, EntityManagerInterface $entityManager ): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $user->setUpdatedAt(new DateTime()); // $form->get('nom')->getData())

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Vous avez modifié votre compte avec succès !");
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/update_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-mon-profil/{id}", name="app_delete_user", methods={"GET|POST"})
     */
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
                if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user);
                 }
                $entityManager->remove($user);
                $entityManager->flush();

                $this->addFlash('success', "L'utilisateur ". $user->getId() ." a bien été supprimé définitivement de la base de données");

                return $this->redirectToRoute('app_home');
            
    }

    /**
     * @Route("/tous-mes-commentaires", name="app_user_show_commentaries", methods={"GET"})
     */
    public function showUserCommentaries(EntityManagerInterface $entityManager): Response
    {
        $commentaries = $entityManager->getRepository(Commentary::class)->findBy(['author' => $this->getUser()]);

        $total = count($commentaries);
        $totalInline = count($entityManager->getRepository(Commentary::class)->findBy(['author' => $this->getUser()]));

        return $this->render('profile/show_user_commentaries.html.twig', [
            'commentaries' => $commentaries,
            'total' => $total,
            'totalInline' => $totalInline,
        ]);
    }

    /**
     * @Route("/voir-mon-commentaire/{id}", name="app_user_show_commentary", methods={"GET"})
     */
    public function showUserCommentary(Commentary $commentary): Response
    {
        return $this->render('profile/show_user_commentary.html.twig', [
            'commentary' => $commentary,
        ]);
    }

    /**
     * @Route("/modifier-mon-commentaire/{id}", name="app_user_update_commentary", methods={"GET|POST"})
     */
    public function updateUserCommentary(Commentary $commentary, Request $request, EntityManagerInterface $entityManager, CommentaryRepository $commentaryRepository): Response
    {
        
        $commentaries = $entityManager->getRepository(Commentary::class)->findBy(['author' => $this->getUser()]);
        $total = count($commentaries);
        $totalInline = count($entityManager->getRepository(Commentary::class)->findBy(['author' => $this->getUser()]));

        $form =$this->createForm(CommentaryFormType::class, $commentary)->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid() === false ) {
            $this->addFlash('warning', 'Votre commentaire est vide !');

            return $this->render('profile/show_user_commentaries.html.twig', [
                'commentaries' => $commentaries,
                'total' => $total,
                'totalInline' => $totalInline,
            ]);
        } // endif

        if($form->isSubmitted() && $form->isValid()) { // === true
            
            $commentary->setupdatedAt(new DateTime());

            $entityManager->persist($commentary);
            $entityManager->flush();
            
            $this->addFlash('success', "Vous avez modifié le commentaire<strong>". $commentary->getId() ." </strong> avec succès !");

            return $this->render('profile/show_user_commentaries.html.twig', [
                'commentaries' => $commentaries,
                'total' => $total,
                'totalInline' => $totalInline,
            ]);
        } // endif

        return $this->render('commentaries/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/supprimer-mon-commentaire/{id}", name="app_user_delete_commentary", methods={"GET|POST"})
     */
    public function deleteUserCommentary(Request $request, Commentary $commentary, EntityManagerInterface $entityManager, CommentaryRepository $commentaryRepository): Response
    {
                if ($this->isCsrfTokenValid('delete'.$commentary->getId(), $request->request->get('_token'))) {
                $commentaryRepository->remove($commentary);
                }

                $entityManager->remove($commentary);
                $entityManager->flush();

                $this->addFlash('success', "Le commentaire ". $commentary->getId() ." a bien été supprimée définitivement de la base de données");

                return $this->redirectToRoute('app_user_show_commentaries');
            
    }
        
    /**
     * @Route("/tous-mes-messages", name="app_user_show_contacts", methods={"GET"})
     */
    public function showUserContacts(EntityManagerInterface $entityManager): Response
    {
        $contacts = $entityManager->getRepository(Contact::class)->findBy(['user' => $this->getUser()]);

        // Statistiques depuis le Controller (voir la vue show_user_commentaries.html.twig)
        $total = count($contacts);
        $totalInline = count($entityManager->getRepository(Contact::class)->findBy([ 'user' => $this->getUser()]));

        return $this->render('profile/show_user_contacts.html.twig', [
            'contacts' => $contacts,
            'total' => $total,
            'totalInline' => $totalInline,
        ]);
    }

    /**
     * @Route("/voir-mon-message/{id}", name="app_user_show_contact", methods={"GET"})
     */
    public function showUserContact(Contact $contact): Response
    {
        return $this->render('profile/show_user_contact.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/modifier-mon-message/{id}", name="app_user_update_contact", methods={"GET|POST"})
     */
    public function updateUserContact(Contact $contact, Request $request, EntityManagerInterface $entityManager, ContactRepository $contactRepository): Response
    {
        
        $contacts = $entityManager->getRepository(Contact::class)->findBy(['user' => $this->getUser()]);
        $total = count($contacts);
        $totalInline = count($entityManager->getRepository(Contact::class)->findBy(['user' => $this->getUser()]));

        $form =$this->createForm(UserContactType::class, $contact)->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid() === false ) {
            $this->addFlash('warning', 'Votre message est vide !');

            return $this->render('profile/show_user_contacts.html.twig', [
                'contacts' => $contacts,
                'total' => $total,
                'totalInline' => $totalInline,
            ]);
        } 

        if($form->isSubmitted() && $form->isValid()) { 
            
            $contact->setupdatedAt(new DateTime());

            $contact->setUser($this->getUser());

            $entityManager->persist($contact);
            $entityManager->flush();
            
            $this->addFlash('success', "Vous avez modifié le message<strong>". $contact->getId() ." </strong> avec succès !");

            return $this->render('profile/show_user_contacts.html.twig', [
                'contacts' => $contacts,
                'total' => $total,
                'totalInline' => $totalInline,
            ]);
        } 

        return $this->render('contacts/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/supprimer-mon-message/{id}", name="app_user_delete_contact", methods={"GET|POST"})
     */
    public function deleteUserMessage(Request $request, Contact $contact, ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
                if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
                $contactRepository->remove($contact);
                }
                $entityManager->remove($contact);
                $entityManager->flush();

                $this->addFlash('success', "Le message ". $contact->getId() ." a bien été supprimée définitivement de la base de données");

                return $this->redirectToRoute('app_user_show_contacts');
            
    }

    /**
     * @Route("/voir mes rdv", name="app_user_show_rdvs", methods={"GET"})
     */
    public function showRdv(EntityManagerInterface $entityManager): Response
    {
        $bookings = $entityManager->getRepository(Booking::class)->findBy(['user' => $this->getUser()]);

        $total = count($bookings);

        return $this->render('profile/show_user_rdvs.html.twig', [
            'bookings' => $bookings,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/voir-mon-rdv/{id}", name="app_user_show_rdv", methods={"GET"})
     */
    public function showUserRdv(Booking $booking): Response
    {
        return $this->render('profile/show_user_rdv.html.twig', [
            'booking' => $booking,
        ]);
    }
    
    /**
     * @Route("/tous-mes-devis", name="app_user_show_bills", methods={"GET"})
     */
    public function showUserBills(EntityManagerInterface $entityManager): Response
    {
        $bills = $entityManager->getRepository(Bill::class)->findBy(['user' => $this->getUser()]);

        // Statistiques depuis le Controller (voir la vue show_user_commentaries.html.twig)
        $total = count($bills);
        $totalInline = count($entityManager->getRepository(Bill::class)->findBy([ 'user' => $this->getUser()]));

        return $this->render('profile/show_user_bills.html.twig', [
            'bills' => $bills,
            'total' => $total,
            'totalInline' => $totalInline,
        ]);
    }

    /**
     * @Route("/voir-mon-devis/{id}", name="app_user_show_bill", methods={"GET"})
     */
    public function showUserBill(Bill $bill): Response
    {
        return $this->render('profile/show_user_bill.html.twig', [
            'bill' => $bill,
        ]);
    }
    

    /**
     * @Route("/tableau-de-bord-utilisateur", name="app_user_show_dashboard", methods={"GET"})
     */
    public function showUserDashboard(EntityManagerInterface $entityManager): Response 
    {
        // try/catch fait parti de PHP nativement, il permet de gérer les class Exception (erreur).
        // On se sert d'un try/catch lorqu'on utilise des méthodes (fonctions) QUI LANCENT (throw) une Exception
        // Si la méthode lance l'erreur pendant son éxécution, alors l'Exception sera 'attrapéé' (catch).
        // Le code dans les accolades du catch sera alors éxécuté.
        // try{
        //    $this->denyAccessUnlessGranted('USER');
        // }
        // catch(AccessDeniedException $exception) {
        //    $this->addFlash('warning', 'Cette partie du site est réservée.');
        //   return $this->redirectToRoute('default_home');
        // }
        // $categories = $entityManager->getRepository(User::class)->findBy(['category' => $this->getUser()]);
    
        $users = $entityManager->getRepository(User::class)->findBy(['id' => $this->getUser()]);
        $categories = $entityManager->getRepository(Category::class)->findBy(['id' => $this->getUser()]);

        $services = $entityManager->getRepository(Service::class)->findBy(['id' => $this->getUser()]);

        $bookings =$entityManager->getRepository(Booking::class)->findBy(['user' => $this->getUser()]);

        $bills =$entityManager->getRepository(Bill::class)->findBy(['user' => $this->getUser()]);

        $contacts =$entityManager->getRepository(Contact::class)->findBy(['user' => $this->getUser()]);

        $commentaries =$entityManager->getRepository(Commentary::class)->findBy(['author' => $this->getUser()]);

        
        $totalUsers = count($users);
        $totalCategories = count($categories);
        $totalServices = count($services);
        $totalBookings = count($bookings);
        $totalBills = count($bills);
        $totalContacts = count($contacts);
        $totalCommentaries = count($commentaries);
        
        
        
        return $this->render('profile/show_user_dashboard.html.twig', [
            'users' => $users,
            'categories' => $categories,
            'services'=> $services, 
            'bookings' => $bookings,
            'bills'=> $bills,
            'contacts'=> $contacts,
            'commentaries'=> $commentaries,
            
            'totalUsers' => $totalUsers,
            'totalServices' => $totalServices,
            'totalCategories' => $totalCategories,
            'totalServices' => $totalServices,
            'totalBookings' => $totalBookings,
            'totalBills' => $totalBills,
            'totalContacts' => $totalContacts,
            'totalCommentaries' => $totalCommentaries,
            
            // 'total' => $total,

            
            
        ]); // redirection dans templates (voir en bas)
    }
}






