<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LuckyControllerTest
 */
class LuckyControllerTest extends WebTestCase
{
    /**
     * Test the /lucky/number route.
     */
    public function testLuckyNumber(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lucky/number');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Lucky number:');
    }

    /**
     * Test the /lucky/hello route.
     */
    public function testLuckyHello(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lucky/hello');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Hello to you!');
    }
}