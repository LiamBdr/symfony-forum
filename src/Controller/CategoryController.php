<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private categoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/categories', name: 'category_all')]
    public function all(): Response
    {
        return $this->render('category/all.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    #[Route('/categories/{slug}', name: 'category_display')]
    public function display(?Category $category): Response
    {
        if (!$category) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/display.html.twig', [
            'category' => $category,
            'allCategory' => $this->categoryRepository->findAll()
        ]);
    }
}
