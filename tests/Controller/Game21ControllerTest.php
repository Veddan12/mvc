<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Card\Game21;
use App\Controller\Game21Controller;

/**
 * Class Game21ControllerTest
 */
class Game21ControllerTest extends WebTestCase
{
    /**
     * Test the landing page for the game 21.
     */
    public function testLandingPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Game 21');
    }

    /**
     * Test the doc page loads.
     */
    public function testDocumentationPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/doc');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Klassbeskrivningar');
    }

    /**
     * Test that the main play page loads successfully
     * and contains a form for gameplay actions.
     */
    public function testPlayGame(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/play');
        $this->assertSelectorExists('form');
    }

    /**
     * Test restarting the game via the /game/restart route
     * redirects back to the play page and loads successfully.
     */
    public function testRestartGame(): void
    {
        $client = static::createClient();

        $client->request('GET', '/game/play');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/game/restart');

        $this->assertResponseRedirects('/game/play');

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }
}
