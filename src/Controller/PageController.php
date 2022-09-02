<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/page/{slug}', name: 'page_display')]
    public function display(string $slug): Response
    {
        return $this->render('page/display.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
