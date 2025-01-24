<?php

namespace App\Story;

use App\Factory\PlaceFactory;
use App\Factory\TagLabelFactory;
use Zenstruck\Foundry\Story;

final class TestPlacesTagsStory extends Story
{
    public function build(): void
    {
        PlaceFactory::createOne();
        PlaceFactory::createOne();

        TagLabelFactory::createOne();
        TagLabelFactory::createOne();
    }
}
