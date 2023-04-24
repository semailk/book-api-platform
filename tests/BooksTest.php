<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Book;

class BooksTest extends ApiTestCase
{

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', 'api/books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            'hydra:view' => [
                '@id' => '/api/books?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/books?page=1',
                'hydra:last' => '/api/books?page=4',
                'hydra:next' => '/api/books?page=2',
            ],
        ]);

        $this->assertCount(30, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Book::class);
    }

    public function testCreateBook(): void
    {
        $response = static::createClient()->request('POST', '/api/books', ['headers' => ['Content-Type' => 'application/ld+json'], 'json' => [
            'isbn' => '9781344037075',
            'title' => 'The Handmaid\'s Tale',
            'description' => 'Brilliantly conceived and executed, this powerful evocation of twenty-first century America gives full rein to Margaret Atwood\'s devastating irony, wit and astute perception.',
            'author' => 'Margaret Atwood'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Book',
            '@type' => 'Book',
            'isbn' => '9781344037075',
            'title' => 'The Handmaid\'s Tale',
            'description' => 'Brilliantly conceived and executed, this powerful evocation of twenty-first century America gives full rein to Margaret Atwood\'s devastating irony, wit and astute perception.',
            'author' => 'Margaret Atwood',
            'reviews' => [],
        ]);

//        $this->assertMatchesRegularExpression('~^/books/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Book::class);
    }

    public function testUpdateBook(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Book::class, ['isbn' => '9781344037075']);


       $client->request('PATCH', $iri, ['headers' => ['Content-Type' => 'application/merge-patch+json'],'json' => [
            'title' => 'updated title',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'isbn' => '9781344037075',
            'title' => 'updated title',
        ]);
    }

    public function testDeleteBook(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Book::class, ['isbn' => '9781344037075']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Book::class)->findOneBy(['isbn' => '9781344037075'])
        );
    }

}