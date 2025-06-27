<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the Product entity.
 */
class ProductTest extends TestCase
{
    /**
     * Test set and get name.
     */
    public function testSetNameAndGetName(): void
    {
        $product = new Product();
        $product->setName("Test Product");

        $this->assertEquals("Test Product", $product->getName());
    }

    /**
     * Test set and get value.
     */
    public function testSetValueAndGetValue(): void
    {
        $product = new Product();
        $product->setValue(38);

        $this->assertEquals(38, $product->getValue());
    }

    /**
     * Test initial id is null.
     */
    public function testInitialIdIsNull(): void
    {
        $product = new Product();

        $this->assertNull($product->getId());
    }
}
