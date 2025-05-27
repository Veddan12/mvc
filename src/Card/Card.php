<?php

namespace App\Card;

class Card
{
    /** @var string */
    private string $suit;

    /** @var string */
    private string $value;

    /**
     * Card constructor.
     *
     * @param string $value
     * @param string $suit
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Get the suit of the card (Hearts, Diamonds, Clubs, Spades).
     *
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Get the value of the card (Ace, 2, 3, King).
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the numeric/point value of the card.
     *
     * @return int
     */
    public function getNumericValue(): int
    {
        return match ($this->value) {
            'King'  => 13,
            'Queen' => 12,
            'Jack'  => 11,
            'Ace'   => 1,
            default => (int)$this->value,
        };
    }

    /**
     * String representation of the card.
     *
     * @return string The card in the format "Value of Suit" -> "King of Hearts".
    */
    public function __toString(): string
    {
        return "{$this->value} of {$this->suit}";
    }


    /**
     * Convert the card to an array.
     *
     * @return array{value: string, suit: string}
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'suit' => $this->suit,
        ];
    }
}
