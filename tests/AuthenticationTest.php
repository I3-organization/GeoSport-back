<?php
// tests/AuthenticationTest.php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class AuthenticationTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testCreateUser():void
    {
        $client = self::createClient();
        $client->request('POST', '/api/users', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => [
                'username' => 'Test',
                'plainPassword' => 'Test123',
                'firstname' => 'Test',
                'lastname' => 'Test',
                'email' => 'user@example.com',
                'biography' => 'Test',
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testLogin(): void
    {
        $client = self::createClient();

        $response = $client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => 'Test',
                'password' => 'Test123',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);
    }
}