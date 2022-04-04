<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/blog', name: 'app_blog_')]
class BlogController extends AbstractController
{

    /**
     * Page principale
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function indexBlog(PostRepository $postRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * Creer une publication
     */
    #[Route('/publier', name: 'new_post', methods: ['GET', 'POST'])]
    public function newPost(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setAuthor($this->getUser());

            $postRepository->add($post);

            // Flash
            $this->addFlash('success', 'publication Ajouté');

            return $this->redirectToRoute('app_blog_index', []);
        }

        return $this->renderForm('blog/new.post.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * Voir une publication
     */
    #[Route('/{slug}', name: 'show_post', methods: ['GET', 'POST'])]
    public function showPost(Request $request, Post $post, CommentRepository $commentRepository): Response
    {

        // Si utilisateur n'est pas connecté, 
        // on envoie directement la vue dans le formulaire
        // Pour optimiser le fonctionnement & la sécurité
        if (!$this->getUser()) {
            return $this->render('blog/show.post.html.twig', [
                'post' => $post,
            ]);
        }

        // Sinom on traite la vue avec le formulaire de commentaire
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On renseigne les infos du commentaire
            // (auteur est publication)
            # Autheur
            $comment
                ->setAuthor($this->getUser())
                ->setPost($post);
            // add en bdd
            $commentRepository->add($comment);

            return $this->redirectToRoute('app_blog_show_post', [
                'slug' => $post->getSlug(),
            ]);
        }

        return $this->renderForm('blog/show.post.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * Editer un article
     */
    #[Route('/{slug}/modifier-publication', name: 'edit_post', methods: ['GET', 'POST'])]
    public function editPost(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $postRepository->add($post);

            // Flash
            $this->addFlash('success', 'publication Modifié');

            return $this->redirectToRoute('app_blog_index', []);
        }

        return $this->renderForm('blog/edit.post.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * Laisser un commentaire
     */
    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On affecte l'utilisateur connecté comme Auteur de la publication
            $comment->setAuthor($this->getUser());
            $commentRepository->add($comment);

            $this->addFlash('success', 'Publication ajoutée');

            return $this->redirectToRoute('app_blog_index');
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * Supprésion d'un article
     */
    #[Route('/{id}', name: 'delete_post', methods: ['POST'])]
    public function deletePost(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('mon_joli_block' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);

            // Flash
            $this->addFlash('success', 'publication Supprimé');
        }

        return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
