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

    /** @var bool Flag for whether the game is over. */
    private bool $gameOver = false;

    /** @var string The result message. */
    private string $result = '';

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
     * Draw a card for player from deck and add it to the player's hand.
     */
    public function drawForPlayer(): void
    {
        $card = $this->deck->drawCard();
        if ($card !== null) {
            $this->playerHand->addCard($card);
        }

        if ($this->playerHand->getTotal() > 21) {
            $this->result = "Du har över 21! Du förlorade!";
            $this->gameOver = true;
        }
    }

    /**
     * Draw a card for bank from deck and add it to the bank's hand.
     */
    public function drawForBank(): void
    {
        while ($this->bankHand->getTotal() < 17) {
            $card = $this->deck->drawCard();
            if ($card === null) {
                return;
            }
            $this->bankHand->addCard($card);
        }
        $this->result = $this->compareResults();
        $this->gameOver = true;
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
     * Check if the game is over.
     *
     * @return bool True if the game has ended, otherwise false.
     */
    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    /**
     * Get the final or current result message.
     *
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }
}
