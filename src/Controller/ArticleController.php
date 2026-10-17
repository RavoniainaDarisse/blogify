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

        $title = $request->request->get('title');
        $excerpt = $request->request->get('excerpt');
        $content = $request->request->get('content');

        $article = new Article();

        $article
            ->setTitle($title)
            ->setExcerpt($excerpt)
            ->setContent($content)
            ->setPublishedAt(new \DateTimeImmutable());

        $em->persist($article);
        $em->flush();

        return new Response('Article enregistré avec succès !');
    }


    
}
