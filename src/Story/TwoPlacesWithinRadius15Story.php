<?php

namespace App\Story;

use App\Factory\PlaceFactory;
use Zenstruck\Foundry\Story;

final class TwoPlacesWithinRadius15Story extends Story
{
    public function build(): void
    {
        PlaceFactory::createOne([
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ]);
        PlaceFactory::createOne([
            'latitude' => 48.8583,
            'longitude' => 2.2944,
        ]);

        PlaceFactory::createOne([
            'latitude' => 49.8566,
            'longitude' => 3.3522,
        ]);
    }
}
