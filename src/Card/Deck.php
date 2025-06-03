<?php

namespace App\Card;

/**
 * Class Deck represents a standard deck of playing cards.
 */
class Deck
{
    /** @var Card[] */
    protected array $cards;
    // private int $remainingCards;

    public function __construct()
    {
        $this->cards = [];
        // $this->remainingCards = 0;

        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $values = ['Ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King'];

        // Generate the deck
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }

        // $this->remainingCards = count($this->cards);
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * Draw a single card from the deck
     *
     * @return Card|null
     */
    public function drawCard(): ?Card
    {

        // if ($this->remainingCards <= 0) {
        //     return null;
        // }
        if (count($this->cards) <= 0) {
            return null;
        }
        // Get a random index for the card to draw
        $randomIndex = (int) array_rand($this->cards);
        $drawnCard = $this->cards[$randomIndex];

        // Remove the drawn card from the deck
        array_splice($this->cards, $randomIndex, 1);
        // $this->remainingCards--;
        return $drawnCard;
    }

    /**
     * Draw multiple cards
     *
     * @return Card[]
     */
    public function drawMoreCards(int $num): array
    {
        $drawnCards = [];

        for ($i = 0; $i < $num; $i++) {
            $drawnCard = $this->drawCard();
            if ($drawnCard === null) {
                return $drawnCards;
            }
            $drawnCards[] = $drawnCard;
        }

        return $drawnCards;
    }

    public function remainingCards(): int
    {
        // return $this->remainingCards;
        return count($this->cards);
    }

    /**
     * Get the cards as arrays.
     *
     * @return array<int, array<string, string>>
     */
    public function getCardsAsArray(): array
    {
        return array_map(function (Card $card) {
            return $card->toArray();
        }, $this->cards);
    }
}
