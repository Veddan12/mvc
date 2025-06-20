<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

use App\Entity\Library;

/**
 * Test cases for the Library entity.
 */
class LibraryTest extends TestCase
{
    /**
     * Test set and get.
     */
    public function testLibraryEntity(): void
    {
        $library = new Library();

        // Test values
        $id = 1;
        $title = "Hard-Boiled Wonderland and the End of the World";
        $author = "Haruki Murakami";
        $isbn = "9780099448785";
        $bookcover = "murakami.jpg";

        // Set values
        $library->setId($id);
        $library->setTitle($title);
        $library->setAuthor($author);
        $library->setIsbn($isbn);
        $library->setBookcover($bookcover);

        // Assert values
        $this->assertEquals($id, $library->getId());
        $this->assertEquals($title, $library->getTitle());
        $this->assertEquals($author, $library->getAuthor());
        $this->assertEquals($isbn, $library->getIsbn());
        $this->assertEquals($bookcover, $library->getBookcover());
    }
}
