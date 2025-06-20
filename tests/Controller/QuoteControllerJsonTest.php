<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class QuoteControllerJsonTest
 */
class QuoteControllerJsonTest extends WebTestCase
{
    /**
     * Test the /api/quote route returns a successful JSON response
     *
     * @return void
     */
    public function testJsonQuote(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');

        // Assert that the response was successful
        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content, 'Response content is not a string.');

        // Decode the JSON response
        $data = json_decode($content, true);
        $this->assertIsArray($data, 'Decoded JSON is not an array.');

        $this->assertArrayHasKey('quote', $data);
    }
}
