<?php
namespace App\Components;

use App\Entity\Comment;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('comment_item')]
class CommentItemComponent
{
    public Comment $comment;

}