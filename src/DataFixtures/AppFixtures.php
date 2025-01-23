<?php

namespace App\DataFixtures;

use App\Story\DefaultPlacesStory;
use App\Story\DefaultTagsStory;
use App\Story\DefaultUsersStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        DefaultPlacesStory::load();
        DefaultTagsStory::load();
        DefaultUsersStory::load();
    }
}