<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\TagLabelFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class TagsLabelsTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        TagLabelFactory::createMany(100);

        static::createClient()->request('GET', '/api/tag_labels');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetEntity(): void
    {
        $tag_label = TagLabelFactory::createOne();

        static::createClient()->request('GET', '/api/tag_labels/' . $tag_label->getId());

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/tag_labels/' . $tag_label->getId(),
            '@type' => 'TagLabel',
        ]);
    }

    public function testCreateEntity(): void
    {
        static::createClient()->request('POST', '/api/tag_labels', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => [
                'name' => 'Running'
            ]]);

        $this->assertResponseStatusCodeSame(201);

        $tag_label = TagLabelFactory::repository()->findOneBy(['name' => 'Running']);

        $this->assertNotNull($tag_label);
    }

    public function testDeleteEntity(): void
    {
        $tag_label = TagLabelFactory::createOne();
        $id = $tag_label->getId();

        static::createClient()->request('DELETE', '/api/tag_labels/' . $tag_label->getId());

        $this->assertResponseStatusCodeSame(204);

        $tag_label = TagLabelFactory::repository()->find($id);

        $this->assertNull($tag_label);
    }
}