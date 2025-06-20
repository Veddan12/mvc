<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Deck;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    /**
     * Test creating a new deck.
     */
    public function testCreateDeck(): void
    {
        $deck = new Deck();
        $this->assertCount(52, $deck->getCards());
    }

    /**
     * Test that shuffling the deck changes the card order.
     */
    public function testShuffle(): void
    {
        $deck1 = new Deck();
        $deck1->shuffle();

        $deck2 = new Deck();
        $deck2->shuffle();

        $this->assertNotEquals($deck1, $deck2);
    }

    /**
     * Test drawing a single card reduces the deck size by one.
     */
    public function testDrawCard(): void
    {
        $deck = new Deck();
        $deck->drawCard();

        $this->assertCount(51, $deck->getCards());
    }

    /**
     * Test drawing multiple cards reduces the deck size accordingly.
     */
    public function testDrawMoreCards(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(5);

        $this->assertCount(47, $deck->getCards());
    }

    /**
     * Test drawing more cards than remaining returns an empty array.
     */
    public function testDrawMoreCardsNoCardsLeft(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(52);
        $drawMore = $deck->drawMoreCards(5);

        $this->assertCount(0, $drawMore);
    }

    /**
     * Test drawing a card from an empty deck returns null.
     */
    public function testDrawCardIfEmpty(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(52);

        $this->assertNull($deck->drawCard());
    }

    /**
     * Test remainingCards returns the correct count after drawing cards.
     */
    public function testRemainingCards(): void
    {
        $deck = new Deck();
        $deck->drawMoreCards(12);

        $this->assertEquals(40, $deck->remainingCards());
    }

    /**
     * Test getCardsAsArray returns an array representation of all cards.
     */
    public function testGetCardsAsArray(): void
    {
        $deck = new Deck();
        $cards = $deck->getCardsAsArray();

        $this->assertNotEmpty($cards);
        $this->assertCount(52, $cards);
    }
}
