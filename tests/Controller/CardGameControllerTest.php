<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CardGameControllerTest
 */
class CardGameControllerTest extends WebTestCase
{
    /**
     * Test the /card route loads successfully.
     *
     * @return void
     */
    public function testCardStart(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
    }

    /**
    * Test the /card/deck/ route displays a sorted deck.
    *
    * @return void
    */
    public function testSortedDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test the /card/deck/shuffle route shuffles the deck.
     *
     * @return void
     */
    public function testShuffleDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test draw a single card.
     *
     * @return void
     */
    public function testDrawCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        $client->request('GET', '/card/deck/draw');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test draw five cards.
     *
     * @return void
     */
    public function testDrawCards(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        // test to draw 5 cards
        $client->request('GET', '/card/deck/draw/5');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test the /card/deck/empty route shows when deck is empty.
     *
     * @return void
     */
    public function testEmptyDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/empty');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test that the /card/session route loads correctly.
     *
     * @return void
     */
    public function testShowSession()
    {
        $client = static::createClient();
        $client->request('GET', '/card/session');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test that delete session data redirects correctly and shows a flash message.
     *
     * @return void
     */
    public function testDeleteSession()
    {
        $client = static::createClient();
        $client->request('GET', '/card/session/delete');

        // Assert redirection to /card/session
        $this->assertResponseRedirects('/card/session');

        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        // Check for flash message
        $this->assertSelectorTextContains('.flash-success', 'Session data has been deleted.');
    }

    /**
     * Test that drawing a card when the deck is empty redirects to /card/deck/empty.
     *
     * @return void
     */
    public function testRedirectWhenDeckIsEmptyDrawCard()
    {
        $client = static::createClient();

        $client->request('GET', '/card/deck/shuffle');

        // Draw 52 cards to emtpy it
        $client->request('GET', '/card/deck/draw/52');

        // Next draw -> no cards redirect to empty deck
        $client->request('GET', '/card/deck/draw');

        $this->assertTrue($client->getResponse()->isRedirect());
        $location = $client->getResponse()->headers->get('Location') ?? '';
        $this->assertStringContainsString('/card/deck/empty', $location);
    }

    /**
     * Test that drawing multiple cards from an empty deck redirects to /card/deck/empty.
     *
     * @return void
     */
    public function testRedirectWhenDeckIsEmptyDrawCards()
    {
        $client = static::createClient();

        $client->request('GET', '/card/deck/shuffle');

        $client->request('GET', '/card/deck/draw/52');

        $client->request('GET', '/card/deck/draw/5');

        $this->assertTrue($client->getResponse()->isRedirect());
        $location = $client->getResponse()->headers->get('Location') ?? '';
        $this->assertStringContainsString('/card/deck/empty', $location);
    }
}
