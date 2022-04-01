<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


#[Route('/', name: 'app_main_')]
class MainController extends AbstractController
{
    /**
     * Page Main
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('main/main.html.twig', []);
    }

    /**
     * Page Contact
     */
    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig', []);
    }

    /**
     * Connexion
     */
    #[Route(path: '/connexion', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si utilisateur est "log", on redirige sur "home"
        if ($this->getUser()) {
            return $this->redirectToRoute('app_main_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Déconnexion
     */
    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
