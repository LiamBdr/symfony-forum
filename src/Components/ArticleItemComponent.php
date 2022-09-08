<?php
namespace App\Components;

use App\Entity\Article;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('article_item')]
class ArticleItemComponent
{
    public Article $article;
}