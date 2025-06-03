<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Deck;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    public function testCreateDeck(): void
    {
        $deck = new Deck();
        $this->assertCount(52, $deck->getCards());
    }

    public function testShuffle(): void
    {
        $deck1 = new Deck();
        $deck1->shuffle();

        $deck2 = new Deck();
        $deck2->shuffle();

        $this->assertNotEquals($deck1, $deck2);
    }

    public function testDrawCard(): void
    {
        $deck = new Deck();
        $deck->drawCard();

        $this->assertCount(51, $deck->getCards());
    }

    public function testDrawMoreCards(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(5);

        $this->assertCount(47, $deck->getCards());
    }

    public function testDrawMoreCardsNoCardsLeft(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(52);
        $drawMore = $deck->drawMoreCards(5);

        $this->assertCount(0, $drawMore);
    }

    public function testDrawCardIfEmpty(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(52);

        $this->assertNull($deck->drawCard());
    }

    public function testRemainingCards(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(12);

        $this->assertEquals(40, $deck->remainingCards());
    }

    public function testGetCardsAsArray(): void
    {
        $deck = new Deck();
        $cards = $deck->getCardsAsArray();

        $this->assertNotEmpty($cards);
        $this->assertCount(52, $cards);
    }
}
