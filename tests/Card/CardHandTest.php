<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardHand;
use App\Card\Card;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Test adding cards to the hand and retrieving them.
     */
    public function testAddCardAndGetCards(): void
    {
        $cardhand = new CardHand();
        $card1 = new Card('Hearts', '8');
        $card2 = new Card('Clubs', 'Jack');

        $cardhand->addCard($card1);
        $cardhand->addCard($card2);

        $cards = $cardhand->getCards();

        $this->assertSame($card1, $cards[0]);
        $this->assertSame($card2, $cards[1]);
        $this->assertCount(2, $cards);
    }

    /**
     * Test converting the card hand to an array format.
     */
    public function testToArray(): void
    {
        $cardhand = new CardHand();
        $cardhand->addCard(new Card('Diamonds', 'Queen'));
        $handArray = $cardhand->toArray();

        $this->assertEquals(['value' => 'Queen', 'suit' => 'Diamonds'], $handArray[0]);
    }

    /**
     * Test the total value calculation of a card hand.
     */
    public function testGetTotal(): void
    {
        $cardhand = new CardHand();
        $cardhand->addCard(new Card('Diamonds', 'Queen'));
        $cardhand->addCard(new Card('Clubs', '5'));
        $cardhand->addCard(new Card('Spades', 'Ace'));

        $this->assertEquals(12 + 5 + 1, $cardhand->getTotal());
    }

    /**
     * Test total value calculation with two Aces.
     */
    public function testGetTotalTwoAces(): void
    {
        $cardhand = new CardHand();
        $cardhand->addCard(new Card('Heats', 'Ace'));
        $cardhand->addCard(new Card('Spades', 'Ace'));

        $this->assertEquals(1 + 14, $cardhand->getTotal());
    }

    /**
     * Test total value calculation with more than two Aces.
     */
    public function testGetTotalMoreAces(): void
    {
        $cardhand = new CardHand();
        $cardhand->addCard(new Card('Heats', 'Ace'));
        $cardhand->addCard(new Card('Spades', 'Ace'));
        $cardhand->addCard(new Card('Diamonds', 'Ace'));

        $this->assertEquals(1 + 14 + 1, $cardhand->getTotal());
    }
}
