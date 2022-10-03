<?php
namespace App\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('comment_form')]
class CommentFormComponent
{
    use DefaultActionTrait;

    public FormView $commentForm;
}