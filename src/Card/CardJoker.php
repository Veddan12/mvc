<?php

namespace App\Card;

/**
 * Class CardJoker extends the standard Deck by adding two Joker cards.
 */
class CardJoker extends Deck
{
    /**
     * CardJoker constructor.
     * Add Jokers to the deck.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cards[] = new Card('🂿', 'Joker');
        $this->cards[] = new Card('🂿', 'Joker');
    }
}
