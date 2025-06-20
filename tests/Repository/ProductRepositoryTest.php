<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;

use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;

/**
* Test that the ProductRepository can be constructed properly.
*/
class ProductRepositoryTest extends TestCase
{
    /**
     * Test that the ProductRepository can be constructed properly.
     */
    public function testConstructProductRepository(): void
    {
        // Create a mock of the ManagerRegistry

        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->createMock(ManagerRegistry::class);

        $repository = new ProductRepository($registry);

        $this->assertInstanceOf("\App\Repository\ProductRepository", $repository);
    }
}
