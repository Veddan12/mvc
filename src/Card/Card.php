<?php

namespace App\Card;

class Card
{
    private $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
    private $values = ['Ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King'];

    public function getDeck(): array
    {
        $deck = [];

        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $deck[] = ['suit' => $suit, 'value' => $value];
            }
        }

        return $deck;
    }
    public function shuffleDeck(array &$deck): void
    {
        shuffle($deck);
    }
}
