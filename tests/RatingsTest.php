<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\PlaceFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class RatingsTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetRating(): void
    {
        $client = self::createClient();

        $rating = RatingFactory::createOne([
            'user' => UserFactory::createOne(),
            'place' => PlaceFactory::createOne()
        ]);

        $client->request('GET', '/api/ratings/' . $rating->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testCreateRating(): void
    {
        $client = self::createClient();

        $user = UserFactory::createOne();
        $place = PlaceFactory::createOne();

        $client->request('POST', '/api/ratings', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => [
                'user' => '/api/users/' . $user->getId(),
                'place' => '/api/places/' . $place->getId(),
                'rate' => 5
            ]]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testUpdateRating(): void
    {
        $client = self::createClient();

        $rating = RatingFactory::createOne([
            'user' => UserFactory::createOne(),
            'place' => PlaceFactory::createOne()
        ]);

        $client->request('PATCH', '/api/ratings/' . $rating->getId(), [
            'headers' => ['Content-Type' => 'application/merge-patch+json'],
            'json' => [
                'rate' => 4
            ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/ratings/' . $rating->getId(),
            'rate' => 4
        ]);
    }

    public function testDeleteRating(): void
    {
        $client = self::createClient();

        $rating = RatingFactory::createOne([
            'user' => UserFactory::createOne(),
            'place' => PlaceFactory::createOne()
        ]);

        $client->request('DELETE', '/api/ratings/' . $rating->getId());

        $this->assertResponseStatusCodeSame(204);
    }

    public function testGetRatingsByUserId(): void
    {
        $client = self::createClient();

        $rating = RatingFactory::createOne([
            'user' => UserFactory::createOne(),
            'place' => PlaceFactory::createOne()
        ]);

        $client->request('GET', '/api/ratings/' . $rating->getId() . '?user.id=' . $rating->getUser()->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testGetRatingsByPlaceId(): void
    {
        $client = self::createClient();

        $rating = RatingFactory::createOne([
            'user' => UserFactory::createOne(),
            'place' => PlaceFactory::createOne()
        ]);

        $client->request('GET', '/api/ratings/' . $rating->getId() . '?place.id=' . $rating->getPlace()->getId());

        $this->assertResponseIsSuccessful();
    }

}