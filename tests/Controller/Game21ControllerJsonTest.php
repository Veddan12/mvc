<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class Game21ControllerJsonTest
 */
class Game21ControllerJsonTest extends WebTestCase
{
    /**
     * Tests api/game when no Game21 is in the session.
     */
    public function testNoGameInSession(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/game');

        $this->assertResponseStatusCodeSame(404);

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);

        $data = json_decode($content, true);
        $this->assertIsArray($data);

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No active game found in session.', $data['error']);
    }

    /*
     * Tests to init a new Game21 and return JSON data.
     */
    public function testGame21(): void
    {
        $client = static::createClient();

        // Trigger game init logic
        $client->request('GET', '/game/play');

        // Call the /api/game
        $client->request('GET', '/api/game');

        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);

        $data = json_decode($content, true);
        $this->assertIsArray($data);

        $this->assertArrayHasKey('player', $data);
        $this->assertArrayHasKey('playerTotal', $data);
        $this->assertArrayHasKey('bank', $data);
        $this->assertArrayHasKey('bankTotal', $data);
    }
}