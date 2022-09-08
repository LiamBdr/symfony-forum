<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('bouton')]
class BoutonComponent
{
    public string $label;
    public string $url;
    public string $color = 'primary';
    public string $size = 'base';
}