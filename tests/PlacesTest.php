<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\PlaceFactory;
use App\Story\TwoPlacesWithinRadius15Story;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PlacesTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testTwoPlacesWithinRadius15(): void
    {
        PlaceFactory::repository()->truncate();
        TwoPlacesWithinRadius15Story::load();

        $count = PlaceFactory::repository()->count([]);
        $this->assertEquals(3, $count);

        $latitude = 48.8566;
        $longitude = 2.3522;
        $radius = 15;

        $client = static::createClient();
        $response = $client->request('GET', '/api/places', [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]
        ]);


        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $response->toArray()['member']);
    }

    public function testInvalidLatitudeType(): void
    {
        $latitude = "string";
        $longitude = 2.3522;
        $radius = 15;

        $client = static::createClient();
        $client->request('GET', '/api/places', [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testInvalidLongitudeType(): void
    {
        $latitude = 48.8566;
        $longitude = "string";
        $radius = 15;

        $client = static::createClient();
        $client->request('GET', '/api/places', [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testInvalidRadiusType(): void
    {
        $latitude = 48.8566;
        $longitude = 2.3522;
        $radius = "string";

        $client = static::createClient();
        $client->request('GET', '/api/places', [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testNoLatitude(): void
    {
        $longitude = 2.3522;
        $radius = 15;

        $client = static::createClient();
        $client->request('GET', '/api/places', [
            'query' => [
                'longitude' => $longitude,
                'radius' => $radius
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testNoLongitude(): void
    {
        $latitude = 48.8566;
        $radius = 15;

        $client = static::createClient();
        $client->request('GET', '/api/places', [
            'query' => [
                'latitude' => $latitude,
                'radius' => $radius
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testNoRadius(): void
    {
        $latitude = 48.8566;
        $longitude = 2.3522;

        $client = static::createClient();
        $client->request('GET', '/api/places', [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

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
                'latitude' => 2.3522,
                'longitude' => 48.8566,
                'name' => "Test",
                'address' => "48 rue du test",
                'email' => 'test@gmail.com',
                'phone' => '0123456789',
                'image' => 'https://picsum.photos/800/600',
            ]]);

        $this->assertResponseStatusCodeSame(201);

        $place = PlaceFactory::repository()->findOneBy(['name' => 'Test']);

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