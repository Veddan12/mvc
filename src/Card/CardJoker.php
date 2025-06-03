<?php

namespace App\Card;

/**
 * Class CardJoker extends the standard Deck by adding two Joker cards.
 */
class CardJoker extends Deck
{
    // private int $jokers = 2;

    // /**
    //  * @return array<int, array{ suit: string, value: string }>
    // */
    // public function getCardsAsArray(): array
    // {
    //     $deck = parent::getCardsAsArray(); // Get the standard deck of cards

    //     // Add jokers to the deck
    //     for ($i = 0; $i < $this->jokers; $i++) {
    //         $deck[] = ['suit' => '🂿', 'value' => 'Joker'];
    //     }

    //     return $deck;
    // }
    public function __construct()
    {
        parent::__construct();

        $this->cards[] = new Card('🂿', 'Joker');
        $this->cards[] = new Card('🂿', 'Joker');
    }
}
