<?php

namespace App\Story;

use App\Factory\TagLabelFactory;
use Zenstruck\Foundry\Story;

final class DefaultTagsStory extends Story
{
    public function build(): void
    {
        TagLabelFactory::createMany(50);
    }
}
