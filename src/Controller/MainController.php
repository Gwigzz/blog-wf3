<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * Affiche les articles
     */
    #[Route('/blog', name: 'blog')]
    public function blog(PostRepository $postRepository)
    {

        $articles = $postRepository->findAll();

        

        return $this->render(
            'main/blog.html.twig',
            [
                'articles' => $articles
            ]
        );
    }

    /**
     * Page ajouter un nouvel article
     */
    #[Route('/creer_un_article', name: 'new_post')]
    public function newArticle(Request $request, PostRepository $postRepository): Response
    {

        $postEntity = new Post();
        $form = $this->createForm(PostFormType::class, $postEntity);

        // On récupère la requète via $request
        $form->handleRequest($request);

        // Gestion de la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // On insert en base de donnée via notre repository
            $postRepository->add($postEntity);

            // Flash message
            $this->addFlash('success', 'publication ajouté');

            /* Autre moyen d'insérrer en bdd */
            // Injecter (ManagerRegistry $doctrine) au seins de la methode
            // $entityManager = $doctrine->getManager();
            // // Le "persist" permet de s'adapater à l'entité demandée
            // $entityManager->persist($form->getData());
            // // Déclanche l'action en base de donnée
            // $entityManager->flush();

            // Retourne la réponse si "submit" & "valide"
            return $this->redirectToRoute('app_main_new_post');
        }



        return $this->renderForm(
            'main/new_article.html.twig',
            [
                'form_post' => $form
            ]
        );
    }
}
