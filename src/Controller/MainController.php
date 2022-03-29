<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Page Main
     */
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/main.html.twig', [
            'controller_name' => 'Main',
        ]);
    }

    /**
     * Page Contact
     */
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig', [
            'controller_name' => 'Contact'
        ]);
    }
}
