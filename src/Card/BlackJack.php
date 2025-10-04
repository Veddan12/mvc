<?php

namespace App\Card;

use App\Card\PayoutBlackJack;

/**
 * Class BlackJack is game logic handler.
 */
class BlackJack
{
    /** @var Deck */
    private Deck $deck;

    /** @var CardHand[] */
    private array $playerHands = [];

    /** @var int Index for the active hand */
    private int $currentHandIndex = 0;

    /** @var CardHand */
    private CardHand $bankHand;

    private BlackJackRules $rules;

    private PayoutBlackJack $payout;

    /** @var bool Flag for whether the game is over. */
    private bool $gameOver = false;

    /** @var string[] The result message. */
    private array $results = [];

    private string $playerName = '';
    private int $bet = 0;
    private int $balance = 0;

    /**
     * Black Jack constructor.
     *
     * Init the game by creating a new deck and empty hands for the player and the bank.
     */
    public function __construct(Deck $deck, BlackJackRules $rules, PayoutBlackJack $payout)
    {
        $this->deck = $deck;
        $this->rules = $rules;
        $this->payout = $payout;
    }

    /**
     * Draw a card for current player hand.
     */
    public function drawForPlayer(): void
    {
        $hand = $this->getCurrentHand();

        if ($hand === null) {
            return;
        }

        $this->dealCardTo($hand);

        if ($hand->getTotalBlackJack() > 21) {
            $this->nextHand();
        }
    }

    public function getCurrentHand(): ?CardHand
    {
        return $this->playerHands[$this->currentHandIndex] ?? null;
    }

    /**
     * Go to next player hand, or bank at the end.
     */
    public function nextHand(): void
    {
        $this->currentHandIndex++;

        if ($this->currentHandIndex >= count($this->playerHands)) {
            $this->drawForBank();
            $this->compareResults();
        }
    }

    public function getCurrentHandIndex(): int
    {
        return $this->currentHandIndex;
    }

    /**
     * Player chooses to "stay" on this hand → move to the next one.
     */
    public function stay(): void
    {
        $this->nextHand();
    }

    /**
     * Public wrapper for drawing bank cards via BlackJackRUles class.
     */
    public function drawForBank(): void
    {
        $this->rules->drawForBank($this->bankHand, $this->deck);
    }

    /**
     * Compare each player hand against the bank
     * and determine the game result.
     */
    public function compareResults(): void
    {
        foreach ($this->playerHands as $index => $hand) {
            $outcome = $this->rules->determineResult($hand, $this->bankHand, $index);

            $this->payout->applyPayout($outcome['result'], $this->bet, $this->balance);

            $this->results[] = $outcome['message'];
        }

        $this->gameOver = true;
    }

    /**
     * Start a new round by creating player hands and dealing cards.
     *
     * @param int $numHands Number of hands the player wants (1-3)
     */
    public function startGame(int $numHands = 1): void
    {
        if ($numHands < 1 || $numHands > 3) {
            $numHands = 1;
        }

        $this->playerHands = [];
        $this->results = [];
        $this->currentHandIndex = 0;
        $this->gameOver = false;

        // Create players hands
        for ($i = 0; $i < $numHands; $i++) {
            $hand = new CardHand();
            $this->dealCardTo($hand);
            $this->dealCardTo($hand);
            $this->playerHands[] = $hand;
        }

        // Crate banks hand
        $this->bankHand = new CardHand();
        $this->dealCardTo($this->bankHand);
        $this->dealCardTo($this->bankHand);

        // Draw bet from balance
        $this->balance -= $this->bet * $numHands;
    }

    /**
     * Deal one card from the deck.
     */
    public function dealCardTo(CardHand $hand): void
    {
        $card = $this->deck->drawCard();
        if ($card !== null) {
            $hand->addCard($card);
        }
    }

    /**
     * @return CardHand[]
     */
    public function getPlayerHands(): array
    {
        return $this->playerHands;
    }

    /**
    * @return string[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    public function getBankHand(): CardHand
    {
        return $this->bankHand;
    }

    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    public function setPlayerName(string $name): void
    {
        $this->playerName = $name;
    }

    public function setBet(int $bet): void
    {
        $this->bet = $bet;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function getBet(): int
    {
        return $this->bet;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $amount): void
    {
        $this->balance = $amount;
    }
}
