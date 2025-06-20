<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LibraryControllerJsonTest
 */
class LibraryControllerJsonTest extends WebTestCase
{
    /**
     * Test the /api/library/books.
     */
    public function testApiShowLibrary(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/library/books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    /**
     * Test the /api/library/book/{isbn} with a valid ISBN.
     */
    public function testApiShowBookIsbn(): void
    {
        $client = static::createClient();

        $isbn = '9780099448785';
        $client->request('GET', "/api/library/book/{$isbn}");

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content, 'Response content is not a string.');

        $data = json_decode($content, true);
        $this->assertIsArray($data, 'Decoded JSON is not an array.');

        $this->assertEquals($isbn, $data['isbn'] ?? null);
    }

    /**
     * Test the /api/library/book/{isbn} with an invalid ISBN.
     */
    public function testApiShowBookIsbnInvalid(): void
    {
        $client = static::createClient();

        $isbn = '1111111-isbn';
        $client->request('GET', "/api/library/book/{$isbn}");

        $this->assertResponseStatusCodeSame(404);
    }
}