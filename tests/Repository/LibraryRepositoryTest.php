<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

/**
* Test that the LibraryRepository can be constructed properly.
*/
class LibraryRepositoryTest extends TestCase
{
    /**
     * Test that the LibraryRepository can be constructed properly.
     */
    public function testConstructLibraryRepository(): void
    {
        // Create a mock of the ManagerRegistry

        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->createMock(ManagerRegistry::class);

        $repository = new LibraryRepository($registry);

        $this->assertInstanceOf("\App\Repository\LibraryRepository", $repository);
    }
}
