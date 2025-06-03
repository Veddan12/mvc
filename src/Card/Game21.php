<?php

namespace App\Card;

/**
 * Class Game21 is game logic handler.
 */
class Game21
{
    /** @var Deck */
    private Deck $deck;

    /** @var CardHand */
    private CardHand $playerHand;

    /** @var CardHand */
    private CardHand $bankHand;

    /**
     * Game21 constructor.
     *
     * Init the game by creating a new deck and empty hands for the player and the bank.
     */
    public function __construct(Deck $deck, CardHand $playerHand, CardHand $bankHand)
    {
        $this->deck = $deck;
        $this->playerHand = $playerHand;
        $this->bankHand = $bankHand;
    }

    /**
     * Get the player's hand.
     *
     * @return CardHand The player's hand.
     */
    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    /**
     * Get the banks's hand.
     *
     * @return CardHand The bank's hand.
     */
    public function getBankHand(): CardHand
    {
        return $this->bankHand;
    }

    /**
     * Draw a card for player from deck and add it to the player's hand.
     */
    public function drawForPlayer(): void
    {
        // $this->playerHand->addCard($this->deck->drawCard());
        $card = $this->deck->drawCard();
        if ($card !== null) {
            $this->playerHand->addCard($card);
        }
    }

    /**
     * Draw a card for bank from deck and add it to the bank's hand.
     */
    public function drawForBank(): void
    {
        // while ($this->bankHand->getTotal() < 17) {
        //     $this->bankHand->addCard($this->deck->drawCard());
        // }
        while ($this->bankHand->getTotal() < 17) {
            $card = $this->deck->drawCard();
            if ($card === null) {
                break;
            }
            $this->bankHand->addCard($card);
        }
    }

    public function compareResults(): string
    {
        $player = $this->playerHand->getTotal();
        $bank = $this->bankHand->getTotal();

        if ($player > 21) {
            return "Du förlorade!";
        }
        if ($bank > 21) {
            return "Du vann!";
        }
        if ($bank >= $player) {
            return "Du förlorade!";
        }
        return "Du vann!";
    }
}
