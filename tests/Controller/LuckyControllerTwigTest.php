<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LuckyControllerTwigTest
 */
class LuckyControllerTwigTest extends WebTestCase
{
    /**
     * Test the /lucky/twig route renders successfully and contains a body tag.
     *
     * @return void
     */
    public function testLuckyTwig(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lucky/twig');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test the home page (/) renders successfully and contains a body tag.
     *
     * @return void
     */
    public function testHome(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test the /about route renders successfully and contains a body tag.
     *
     * @return void
     */
    public function testAbout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test the /report route renders successfully and contains a body tag.
     *
     * @return void
     */
    public function testReport(): void
    {
        $client = static::createClient();
        $client->request('GET', '/report');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test the /api route renders successfully and contains a body tag.
     *
     * @return void
     */
    public function testApi(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test the /card route renders successfully.
     *
     * @return void
     */
    public function testCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test the /game route renders successfully.
     *
     * @return void
     */
    public function testGame(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test the /library route renders successfully.
     *
     * @return void
     */
    public function testLibrary(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test the /metrics route renders successfully and contains a body tag.
     *
     * @return void
     */
    public function testMetrics(): void
    {
        $client = static::createClient();
        $client->request('GET', '/metrics');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
}
