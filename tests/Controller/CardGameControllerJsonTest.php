<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CardGameControllerJsonTest
 */
class CardGameControllerJsonTest extends WebTestCase
{
    /**
     * Test the GET /api/deck to retrieve the current deck.
     *
     * @return void
     */
    public function testGetDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test the POST /api/deck/shuffle to shuffle the deck.
     *
     * @return void
     */
    public function testShuffleDeck(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test draw a single card from.
     *
     * @return void
     */
    public function testDrawCard(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');
        $client->request('POST', '/api/deck/draw');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test draw multiple cards.
     *
     * @return void
     */
    public function testDrawCards(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');
        $client->request('POST', '/api/deck/draw/5');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test that drawing a card when the deck is empty gives error response.
     * @return void
     */
    public function testDrawCardWhenEmptyDeck(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/deck');

        $client->request('POST', '/api/deck/draw/54');

        $client->request('POST', '/api/deck/draw');

        $this->assertResponseStatusCodeSame(400);

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $data = json_decode($content ?: '', true);
        $this->assertIsArray($data);
        $this->assertEquals('The deck is empty.', $data['error'] ?? null);
    }
}