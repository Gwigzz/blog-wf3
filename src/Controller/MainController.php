<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Repository\PostRepository;
// Import Register
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/', name: 'app_main_')]
class MainController extends AbstractController
{
    /**
     * Page Main
     */
    #[Route(path: '/', name: 'home')]
    public function index(PostRepository $postRepository): Response
    {
        // Dérnière publication sur la page d'accueil (5 max)
        $posts = $postRepository->findBy([], ['createdAt' => 'desc'], 5);

        return $this->render('main/main.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * Page Contact
     */
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération des données du formulaire
            $datasForm = $form->getData();

            // Si téléphone pas renseigné, on traite
            $phone = $datasForm['telephone'] ? $datasForm['telephone'] : 'téléphone non renseigné';

            // Création du message
            $email = (new Email())
                ->from('blog@email.fr')
                ->to('jhonguetter199@hotmail.fr')
                ->subject('Email reçus de ' . $datasForm['username'] . '')
                ->html(
                    '<p>Téléphone : '       . $phone . '</p>
                    <p>Prénom : '           . $datasForm['username'] . '</p>
                    <p>Email :'             . $datasForm['email'] . '</p>
                    <p style="color: #0C91FF"><b>Message</b> : ' . $datasForm['message'] . '</p>
                    '
                );

            // Try to send you'r mail
            try {
                $mailer->send($email);
                $this->addFlash('success', 'message envoyé');
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('warning', 'Une erreur est survenue, merci de réessayer');
            }
            return $this->redirectToRoute('app_main_contact');
        }

        return $this->renderForm('main/contact.html.twig', [
            'contact_form' => $form,
        ]);
    }

    /**
     * Inscription
     */
    #[Route(path: '/inscription', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {

        // Si utilisateur est "log", on redirige sur "home"
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit');
            return $this->redirectToRoute('app_main_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $userRepository->add($user);
            // do anything else you need here, like send an email

            $this->addFlash('success', 'Inscription réussite');

            return $this->redirectToRoute('app_main_login');
        }

        return $this->renderForm('main/registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * Connexion
     */
    #[Route(path: '/connexion', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si utilisateur est "log", on redirige sur "home"
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes déjà connecté');
            return $this->redirectToRoute('app_main_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Déconnexion
     */
    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    /**
     * Recherche (Post & Title)
     */
    #[Route(path: '/recherche', name: 'search', methods: ['GET'])]
    public function search(Request $request, PostRepository $postRepository, PaginatorInterface $paginatorInterface): Response
    {
        // On récupère le contenu du champ de recherche
        $search = $request->query->get('search', '');
        // condition si "$search" et vide ou pas
        if (isset($search) && !empty($search)) {

            // Création d'une requête avec le manager
            $resultat_search = $postRepository->findBySearch($search);

            // Systeme de pagination de résultat de recherche
            // Récupération du numéro de la page demandée
            $requestPage = $request->query->getInt('page', 1);
            if ($requestPage < 1) {
                throw new NotFoundHttpException();
            }
            $post_paginate = $paginatorInterface->paginate(
                $resultat_search, // Requête de récupération
                $requestPage,
                6
            );
        } else {
            $resultat_search = [];
            $post_paginate = [];
        }
        return $this->render('blog/search.post.html.twig', [
            'resultat_posts' => $resultat_search,
            'resultat_pagination' => $post_paginate,
        ]);

        // Réponse -> envoyer une page contenant les éléments trouvés

    }
}
