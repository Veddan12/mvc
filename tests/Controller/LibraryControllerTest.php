<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Library;
use App\Repository\LibraryRepository;

/**
 * Class LibraryControllerTest
 */
class LibraryControllerTest extends WebTestCase
{
    private function getMockedClient(): \Symfony\Bundle\FrameworkBundle\KernelBrowser
    {
        $book = new Library();
        $book->setTitle('Mock Book');
        $book->setAuthor('Mock Author');
        $book->setIsbn('1234567890');
        $book->setBookcover('cover.jpg');
        $book->setId(1);

        $mockRepo = $this->createMock(LibraryRepository::class);
        $mockRepo->method('findAll')->willReturn([$book]);
        $mockRepo->method('find')->willReturn($book);

        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->getContainer()->set(LibraryRepository::class, $mockRepo);

        return $client;
    }

    /**
     * Test the library index.
     */
    public function testIndexPage(): void
    {
        $client = $this->getMockedClient();
        $client->request('GET', '/library');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Library');
    }

    /**
     * Test the create book form page.
     */
    public function testCreateBookForm(): void
    {
        $client = $this->getMockedClient();
        $client->request('GET', '/library/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testViewBookById(): void
    {
        $client = $this->getMockedClient();
        $client->request('GET', '/library/view/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Mock Book');
    }

    public function testViewLibrary(): void
    {
        $client = $this->getMockedClient();
        $client->request('GET', '/library/view');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test loading the update book form by ID.
     */
    public function testUpdateBook(): void
    {
        $client = $this->getMockedClient();
        $client->request('GET', '/library/update/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test the delete book.
     */
    public function testDeleteBook(): void
    {
        $client = $this->getMockedClient();
        $client->request('GET', '/library/delete/1');
        
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test non-existent book ID gives 404 error.
     */
    public function testGetBookNotFound(): void
    {   
        $client = static::createClient();

        $mockRepo = $this->createMock(LibraryRepository::class);
        $mockRepo->method('find')->willReturn(null);
        $client->getContainer()->set(LibraryRepository::class, $mockRepo);

        $client->request('GET', '/library/view/999');
        $this->assertResponseStatusCodeSame(404);
    }
}