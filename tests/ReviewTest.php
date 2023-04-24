<?php


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Review;

class ReviewTest extends ApiTestCase
{
    private $createReview;
    protected function setUp(): void
    {
       $bookCreate = static::createClient()->request('POST', '/api/books', ['headers' => ['Content-Type' => 'application/ld+json'], 'json' => [
            'isbn' => '9781344037075',
            'title' => 'The Handmaid\'s Tale',
            'description' => 'Brilliantly conceived and executed, this powerful evocation of twenty-first century America gives full rein to Margaret Atwood\'s devastating irony, wit and astute perception.',
            'author' => 'Margaret Atwood'
        ]]);

        $this->createReview = static::createClient()->request('POST', '/api/reviews', ['headers' => ['Content-Type' => 'application/json'], 'json' => [
            'rating' => 4,
            'body' => 'The Handmaid\'s Tale ' . rand(1000 , 1000000),
            'author' => 'Margaret Atwood',
            'book' => $bookCreate->toArray()['@id']
        ]]);

    }
    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', 'api/reviews');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            'hydra:view' => [
                '@id' => '/api/reviews?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/reviews?page=1',
                'hydra:last' => '/api/reviews?page=4',
                'hydra:next' => '/api/reviews?page=2',
            ],
        ]);

        $this->assertCount(30, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Review::class);
    }

    public function testCreateReview(): void
    {
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Review',
            '@type' => 'Review',
            'rating' => 4,
            'body' => $this->createReview->toArray()['body'],
            'author' => 'Margaret Atwood'
        ]);

//        $this->assertMatchesRegularExpression('~^/books/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Review::class);
    }

    public function testDeleteReview(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Review::class, ['body' => $this->createReview->toArray()['body']]);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Review::class)->findOneBy(['body' => $this->createReview->toArray()['body']])
        );
    }

    public function testUpdateReview(): void
    {
        $client = static::createClient();

        $iri = $this->findIriBy(Review::class, ['body' => $this->createReview->toArray()['body']]);

        $client->request('PATCH', $iri, ['headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => [
            'body' => 'updated body',
        ]]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            '@id' => $iri,
            'author' => 'Margaret Atwood',
            'body' => 'updated body',
            'book' => $this->createReview->toArray()['book']
        ]);
    }
}