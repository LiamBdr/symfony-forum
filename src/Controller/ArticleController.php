<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'article_display')]
    public function display(?Article $article, ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        if (!$article) {
            return $this->redirectToRoute('app_home');
        }

        //get categories of the current article
        $currentCategories = $article->getCategories();
        $categoriesId = [];
        foreach ($currentCategories as $category) {
            $categoriesId[] = $category->getId();
        }
        $nbCategories = count($categoriesId);

        $availableArticles = [];
        $nbAvailableArticles = 0;
        //get all articles of each categories
        foreach ($categoriesId as $catId) {
            $availableArticles[$catId] = $categoryRepository->find($catId)->getArticles();
            $nbAvailableArticles += count($availableArticles[$catId]);
        }

        //TODO : select recommended articles by most viewed articles
        $recommendedArticles = [];
        $nbrecommendedArticles = 4;

        if ($nbAvailableArticles > $nbrecommendedArticles) {

            for ($i = 0; $i < $nbrecommendedArticles; $i++) {

                //get random categories of the current article
                $randCat = $availableArticles[$categoriesId[rand(0, $nbCategories - 1)]];
                //get random article of the random category
                $randArticle = $randCat[rand(0, count($randCat) - 1)];

                if ($randArticle !== $article && !in_array($randArticle, $recommendedArticles)) {
                    $recommendedArticles[] = $randArticle;
                } else {
                    $i--;
                }
            }

        } else {

            foreach ($availableArticles as $articles) {
                foreach ($articles as $randArticle) {
                    if ($randArticle !== $article && !in_array($randArticle, $recommendedArticles)) {
                        $recommendedArticles[] = $randArticle;
                    }
                }
            }

            $allArticles = $articleRepository->findAll();
            $totalArticles = count($allArticles);

            while (count($recommendedArticles) < $nbrecommendedArticles) {
                $randArticle = $allArticles[rand(0, $totalArticles - 1)];
                if ($randArticle !== $article && !in_array($randArticle, $recommendedArticles)) {
                    $recommendedArticles[] = $randArticle;
                }
            }

        }

        return $this->render('article/display.html.twig', [
            'article' => $article,
            'recommendedArticles' => $recommendedArticles,
        ]);
    }
}
