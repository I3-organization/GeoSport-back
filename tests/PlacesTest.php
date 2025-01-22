<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\PlaceFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PlacesTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        PlaceFactory::createMany(100);

        static::createClient()->request('GET', '/api/places');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetEntity(): void
    {
        $place = PlaceFactory::createOne();

        static::createClient()->request('GET', '/api/places/' . $place->getId());

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/places/' . $place->getId(),
            '@type' => 'Place',
        ]);
    }

    public function testCreateEntity(): void
    {
        static::createClient()->request('POST', '/api/places', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => [
                'latitude' => 48.85341,
                'longitude' => 2.3488,
                'name' => 'Decathlon'
            ]]);

        $this->assertResponseStatusCodeSame(201);

        $place = PlaceFactory::repository()->findOneBy(['name' => 'Decathlon']);

        $this->assertNotNull($place);
    }

    public function testDeleteEntity(): void
    {
        $place = PlaceFactory::createOne();
        $id = $place->getId();

        static::createClient()->request('DELETE', '/api/places/' . $place->getId());

        $this->assertResponseStatusCodeSame(204);

        $place = PlaceFactory::repository()->find($id);

        $this->assertNull($place);
    }

}