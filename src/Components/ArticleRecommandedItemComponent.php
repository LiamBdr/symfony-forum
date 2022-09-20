<?php
namespace App\Components;

use App\Entity\Article;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('article_recommanded_item')]
class ArticleRecommandedItemComponent
{
    public Article $article;
}