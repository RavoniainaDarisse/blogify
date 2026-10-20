<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response 
    {
        $articles = $articleRepository->findBy(
            [],
            ['publishedAt' => 'DESC']
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

   

    #[Route('/article/new', name:'article_new',methods:['GET','POST'])]
    public function newArticle(Request $request , EntityManagerInterface $em) : Response
    {
        if($request->isMethod('GET')){
            return $this->render('article/new.html.twig');
        }

        $title = trim((string) $request->request->get('title', ''));
        $excerpt = trim((string) $request->request->get('excerpt', ''));
        $content = trim((string) $request->request->get('content', ''));

        if ($title === "" || $excerpt === "" || $content === "") {
            $this->addFlash("error","champs n'est doit pas etre vide");
            return $this->redirectToRoute('article_new');
        }

        $article = new Article();

        $article
            ->setTitle($title)
            ->setExcerpt($excerpt)
            ->setContent($content)
            ->setPublishedAt(new \DateTimeImmutable());

        $em->persist($article);
        $em->flush();

        $this->addFlash('success','Article enregistré avec succès !');

        return $this->redirectToRoute('article_index');
    }

    #[Route('/article/{id}',name:'article_show',methods:['GET'])]
    public function show(int $id, ArticleRepository $articleRepository)
    {

        $ar = $articleRepository->find($id);

        if ($ar === null) {
            throw $this->createNotFoundException(
                'Cet article n’existe pas.'
            );
        }

        return $this->render('article/show.html.twig', [
        'article' => $ar,
    ]);
    }



    #[Route('/articles/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        ArticleRepository $articleRepository,
        EntityManagerInterface $em
    ): Response {
        $article = $articleRepository->find($id);

        if ($article === null) {
            throw $this->createNotFoundException(
                'Cet article n’existe pas.'
            );
        }

        if ($request->isMethod('GET')) {
            return $this->render('article/edit.html.twig', [
                'article' => $article,
            ]);
        }

        $title = trim((string) $request->request->get('title', ''));
        $excerpt = trim((string) $request->request->get('excerpt', ''));
        $content = trim((string) $request->request->get('content', ''));

        if ($title === '' || $excerpt === '' || $content === '') {
            return new Response(
                'Erreur : tous les champs sont obligatoires.',
                Response::HTTP_BAD_REQUEST
            );
        }

        $article
            ->setTitle($title)
            ->setExcerpt($excerpt)
            ->setContent($content);

        $em->flush();

        $this->addFlash('success', 'Article modifié avec succès !');

        return $this->redirectToRoute('article_show', [
            'id' => $article->getId(),
        ]);
    }



    
}
