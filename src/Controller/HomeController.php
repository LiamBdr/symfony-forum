<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRep): Response
    {
        return $this->render('home/index.html.twig', [
            'articles' => $articleRep->findBy(
                [],
                ['createdAt' => 'DESC'],
                4
            )]);
    }
}
