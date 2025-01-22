<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $this->loadPlaces($faker, $manager);

        $manager->flush();
    }

    /**
     * @param \Faker\Generator $faker
     * @param ObjectManager $manager
     * @return void
     */
    private function loadPlaces(\Faker\Generator $faker, ObjectManager $manager): void
    {
        for ($i = 0; $i < 25; $i++) {
            $place = new Place();
            $place->setName($faker->streetName());
            $place->setLatitude($faker->latitude());
            $place->setLongitude($faker->longitude());
            $manager->persist($place);
        }
    }
}