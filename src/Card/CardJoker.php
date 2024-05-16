<?php

namespace App\Card;

class CardJoker extends Card
{
    private $jokers = 2;

    public function getDeck(): array
    {
        $deck = parent::getDeck(); // Get the standard deck of cards

        // Add jokers to the deck
        for ($i = 0; $i < $this->jokers; $i++) {
            $deck[] = ['suit' => '🂿', 'value' => 'Joker'];
        }

        return $deck;
    }
}
