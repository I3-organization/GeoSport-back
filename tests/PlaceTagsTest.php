<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Story\TestPlacesTagsStory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PlaceTagsTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testAddTagToPlace(): void
    {
        TestPlacesTagsStory::load();

        $client = static::createClient();

        $response = $client->request('PATCH', '/api/places/1', [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'tags' => [
                    '/api/tag_labels/1',
                    '/api/tag_labels/2'
                ]
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testGetTagsFromPlace(): void
    {
        $client = static::createClient();
        $response = $client->request('GET', '/api/places/1');

        $this->assertResponseIsSuccessful();
        $data = $response->toArray();
        $this->assertArrayHasKey('tags', $data);
        $this->assertCount(2, $data['tags']);
    }

    public function testAddPlaceToTag(): void
    {
        $client = static::createClient();

        $response = $client->request('PATCH', '/api/tag_labels/1', [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'places' => [
                    '/api/places/1',
                ]
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testGetPlacesFromTag(): void
    {
        $client = static::createClient();
        $response = $client->request('GET', '/api/tag_labels/1');

        $this->assertResponseIsSuccessful();
        $data = $response->toArray();
        $this->assertArrayHasKey('places', $data);
        $this->assertCount(1, $data['places']);
    }

    public function testRemoveTagFromPlace(): void
    {
        $client = static::createClient();

        $response = $client->request('PATCH', '/api/places/1', [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'tags' => []
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $response = $client->request('GET', '/api/places/1');
        $data = $response->toArray();
        $this->assertArrayHasKey('tags', $data);
        $this->assertCount(0, $data['tags']);
    }

    public function testRemovePlaceFromTag(): void
    {
        $client = static::createClient();

        $response = $client->request('PATCH', '/api/tag_labels/1', [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'places' => []
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $response = $client->request('GET', '/api/tag_labels/1');
        $data = $response->toArray();
        $this->assertArrayHasKey('places', $data);
        $this->assertCount(0, $data['places']);
    }
}