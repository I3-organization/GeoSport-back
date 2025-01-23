<?php

namespace App\Story;

use App\Factory\PlaceFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class DefaultRatingsStory extends Story
{
    public function build(): void
    {
        RatingFactory::createMany(10, function() {
            return [
                'user' => UserFactory::random(),
                'place' => PlaceFactory::random()
            ];
        });
    }
}
