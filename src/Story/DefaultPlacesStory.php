<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\PlaceFactory;
final class DefaultPlacesStory extends Story
{
    public function build(): void
    {
        PlaceFactory::createMany(100);
    }
}
