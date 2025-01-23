<?php

namespace App\Tests;


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\UserFactory;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UsersTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testCreateUser(): void
    {
        $user = UserFactory::createOne();

        $this->assertNotNull($user->getId());
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testGetUsers(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
    }

    public function testGetUser(): void
    {
        $user = UserFactory::createOne();

        $client = static::createClient();

        $client->request('GET', '/api/users/' . $user->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testInvalidEmail(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/users', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => [
                'email' => 'invalid-email',
                'plainPassword' => 'password',

            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
    }
}