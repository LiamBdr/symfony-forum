<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('section_header')]
class SectionHeaderComponent
{
    public string $title;
    public string $description;
    public string $titleTag = 'h2';
}