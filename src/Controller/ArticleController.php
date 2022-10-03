<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private CategoryRepository $categoryRepository;
    private ObjectManager $entityManager;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, ManagerRegistry $doctrine)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $doctrine->getManager();

        date_default_timezone_set('Europe/Paris');
    }

    #[Route('/article/{slug}', name: 'article_display')]
    public function display(?Article $article, Request $request): Response
    {
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $comment = new Comment($article);
        //TODO : get user id from session
        //$comment->setUser($this->getUser());
        $user = $this->entityManager->getRepository(User::class)->find(1);
        $comment->setUser($user);
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté.');

            return $this->redirectToRoute('article_display', ['slug' => $article->getSlug()]);
        }

        $recommendedArticles = $this::recommendedArticles($article);

        return $this->renderForm('article/display.html.twig', [
            'article' => $article,
            'recommendedArticles' => $recommendedArticles,
            'commentForm' => $commentForm,
        ]);
    }

    private function recommendedArticles($article): array {

        //get categories of the current article
        $currentCategories = $article->getCategories();
        $categoriesId = [];
        foreach ($currentCategories as $category) {
            $categoriesId[] = $category->getId();
        }
        $nbCategories = count($categoriesId);

        $availableArticles = [];
        $nbAvailableArticles = 0;
        //get all articles of each current article categories
        foreach ($categoriesId as $catId) {
            $availableArticles[$catId] = $this->categoryRepository->find($catId)->getArticles();
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

            $allArticles = $this->articleRepository->findAll();
            $totalArticles = count($allArticles);

            while (count($recommendedArticles) < $nbrecommendedArticles) {
                $randArticle = $allArticles[rand(0, $totalArticles - 1)];
                if ($randArticle !== $article && !in_array($randArticle, $recommendedArticles)) {
                    $recommendedArticles[] = $randArticle;
                }
            }

        }

        return $recommendedArticles;
    }

}
