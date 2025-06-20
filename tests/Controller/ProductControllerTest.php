<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ProductControllerTest
 */
class ProductControllerTest extends WebTestCase
{
    /**
     * Test that the product index page.
     */
    public function testIndexPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test that creating a product works and returns
     * a confirmation message containing the new product ID.
     */
    public function testCreateProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/create');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        $this->assertIsString($content, 'Response content is not a string.');
        $this->assertStringContainsString('Saved new product with id', $content);
    }

    /**
     * Test the route show all products returns
     * successful response.
     */
    public function testShowAllProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/show');

        $client->getResponse()->getStatusCode() === 200;
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test showing a product by its ID.
     */
    public function testShowProductById(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/show/1');

        if ($client->getResponse()->getStatusCode() === 200) {
            $this->assertResponseIsSuccessful();
            return;
        }
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * Test view all products page loads successfully.
     */
    public function testViewAllProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/view');

        $client->getResponse()->getStatusCode() === 200;
        $this->assertResponseIsSuccessful();
    }
}
