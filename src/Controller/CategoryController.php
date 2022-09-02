<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'category_display')]
    public function display(string $slug): Response
    {
        return $this->render('category/display.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
