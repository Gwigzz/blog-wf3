<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'app_blog_')]
class PostController extends AbstractController
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
    #[Route('/{id}', name: 'show_post', methods: ['GET'])]
    public function showPost(Post $post): Response
    {
        return $this->render('blog/show.post.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * Editer un article
     */
    #[Route('/{id}/modifier-publication', name: 'edit_post', methods: ['GET', 'POST'])]
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
