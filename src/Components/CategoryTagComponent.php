<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('category_tag')]
class CategoryTagComponent
{
    public object $categories;

}