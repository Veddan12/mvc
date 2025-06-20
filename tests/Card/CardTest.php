<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
     /**
     * Test creating an instance of Card.
     */
    public function testCreateCard(): void
    {
        $card = new Card('Hearts', 'Ace');
        $this->assertInstanceOf(Card::class, $card);
    }

    /**
     * Test getting the suit of the card.
     */
    public function testGetSuire(): void
    {
        $card = new Card('Clubs', 'Ace');
        $this->assertEquals('Clubs', $card->getSuit());
    }

    /**
     * Test getting the value of the card.
     */
    public function testGetValue(): void
    {
        $card = new Card('Spades', 'Ace');
        $this->assertEquals('Ace', $card->getValue());
    }

    /**
     * Test getting the numeric value cards.
     */
    public function testNumericValue(): void
    {
        $king = new Card('Diamond', 'King');
        $queen = new Card('Hearts', 'Queen');
        $jack = new Card('Spades', 'Jack');
        $ace = new Card('Clubs', 'Ace');

        $card = new Card('Hearts', '9');

        $this->assertEquals(13, $king->getNumericValue());
        $this->assertEquals(12, $queen->getNumericValue());
        $this->assertEquals(11, $jack->getNumericValue());
        $this->assertEquals(1, $ace->getNumericValue());

        $this->assertEquals(9, $card->getNumericValue());
    }

    /**
     * Test the string representation of the card.
     */
    public function testToString(): void
    {
        $card = new Card('Spades', 'Jack');

        $this->assertEquals('Jack of Spades', (string)$card);
    }

    /**
     * Test converting the card to an array.
     */
    public function testToArray(): void
    {
        $card = new Card('Hearts', '9');
        $cardArray = ['value' => '9', 'suit' => 'Hearts'];

        $this->assertEquals($cardArray, $card->toArray());
    }
}
