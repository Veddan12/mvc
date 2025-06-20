<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardJoker;

/**
 * Test cases for class CardJoker.
 */
class CardJokerTest extends TestCase
{
    /**
     * Test creating an instance of CardJoker.
     */
    public function testCreateCardJoker(): void
    {
        $deck = new CardJoker();
        $this->assertInstanceOf(CardJoker::class, $deck);
    }
}
