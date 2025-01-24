<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\PlaceFactory;
use App\Factory\ReviewFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReviewsTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testCreateReview(): void
    {
        $client = self::createClient();

        $user = UserFactory::new()->create();
        $place = PlaceFactory::new()->create();

        $client->request('POST', '/api/reviews', [
            'headers' => ['content-type' => 'application/ld+json'],
            'json' => [
                'title' => 'Great place',
                'comment' => 'I had a great time',
                'user' => '/api/users/' . $user->getId(),
                'place' => '/api/places/' . $place->getId(),
            ]]);

        $this->assertResponseIsSuccessful();
    }

    public function testGetReviews(): void
    {
        $client = self::createClient();

        $client->request('GET', '/api/reviews');

        $this->assertResponseIsSuccessful();
    }

    public function testGetReview(): void
    {
        $client = self::createClient();

        $user = UserFactory::new()->create();
        $place = PlaceFactory::new()->create();
        $review = ReviewFactory::new()->create([
            'user' => $user,
            'place' => $place,
        ]);

        $client->request('GET', '/api/reviews/' . $review->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateReview(): void
    {
        $client = self::createClient();

        $user = UserFactory::new()->create();
        $place = PlaceFactory::new()->create();
        $review = ReviewFactory::new()->create([
            'user' => $user,
            'place' => $place,
        ]);

        $client->request('PATCH', '/api/reviews/' . $review->getId(), [
            'headers' => ['content-type' => 'application/merge-patch+json'],
            'json' => [
                'title' => 'Great place',
                'comment' => 'I had a great time',
                'user' => '/api/users/' . $user->getId(),
                'place' => '/api/places/' . $place->getId(),
            ]]);

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteReview(): void
    {
        $client = self::createClient();

        $user = UserFactory::new()->create();
        $place = PlaceFactory::new()->create();
        $review = ReviewFactory::new()->create([
            'user' => $user,
            'place' => $place,
        ]);

        $client->request('DELETE', '/api/reviews/' . $review->getId());

        $this->assertResponseStatusCodeSame(204);
    }

    public function testGetReviewsCollection(): void
    {
        $client = self::createClient();

        $user = UserFactory::new()->create();
        $place = PlaceFactory::new()->create();
        ReviewFactory::new()->create([
            'user' => $user,
            'place' => $place,
        ]);

        $client->request('GET', '/api/reviews');

        $this->assertResponseIsSuccessful();
    }

    public function testGetReviewsByUser(): void
    {
        $client = self::createClient();

        $user = UserFactory::new()->create();
        $place = PlaceFactory::new()->create();
        ReviewFactory::new()->create([
            'user' => $user,
            'place' => $place,
        ]);

        $client->request('GET', '/api/reviews?user.id=' . $user->getId());

        $this->assertResponseIsSuccessful();
    }
}