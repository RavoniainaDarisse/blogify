<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index() : Response
    {

        $articles = [
            [
                'title' => 'Découvrir Symfony',
                'category' => 'Développement',
                'excerpt' => 'Comprendre les bases du framework PHP Symfony.',
            ],
            [
                'title' => 'Apprendre Bootstrap',
                'category' => 'Frontend',
                'excerpt' => 'Créer des interfaces web responsives plus rapidement.',
            ],
            [
                'title' => 'Les bonnes pratiques Git',
                'category' => 'Outils',
                'excerpt' => 'Organiser son code et travailler avec des branches.',
            ],
        ];

    //    $em= $this->getDoctrine()->getManager();

        dump($articles);
    
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}