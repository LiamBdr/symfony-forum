<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'article_display')]
    public function display(string $slug): Response
    {
        return $this->render('article/display.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}
