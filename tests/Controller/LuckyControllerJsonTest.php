<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LuckyControllerJsonTest
 */
class LuckyControllerJsonTest extends WebTestCase
{
    /**
     * Test the /api/lucky/number route.
     *
     * @return void
     */
    public function testJsonLuckyNumber(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/lucky/number');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content, 'Response content is not a string.');

        $data = json_decode($content, true);
        $this->assertIsArray($data, 'Decoded JSON is not an array.');

        $this->assertArrayHasKey('lucky-number', $data);
        $this->assertArrayHasKey('lucky-message', $data);
        $this->assertIsInt($data['lucky-number']);
        $this->assertSame('Hi there!', $data['lucky-message']);
    }
}
