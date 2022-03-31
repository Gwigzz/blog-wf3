<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
}
