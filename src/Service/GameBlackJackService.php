<?php

namespace App\Service;

use App\Card\BlackJack;
use App\Card\BlackJackRules;
use App\Card\Deck;
use App\Card\PayoutBlackJack;

/**
 * Service class for handling the core logic of the BlackJack game.
 *
 * This class handles the game setup, handling of user actions,
 * validating bets.
 */
class GameBlackJackService
{
    /**
     * Create a new instance of the BlackJack game with a fresh deck and hands.
     *
     * @return BlackJack A new game instance with shuffled deck and empty hands.
     */
    private function createGameInstance(): BlackJack
    {
        $deck = new Deck();
        $deck->shuffle();

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        return new BlackJack($deck, $rules, $payout);
    }

    /**
     * Create a brand new BlackJack game with initial player settings.
     *
     * @param string $playerName The name of the player.
     * @param int $balance The starting balance for the player.
     * @param int $bet The amount the player bets.
     * @param int $numHands Number of hands to play this round.
     * @return BlackJack Initialized BlackJack game.
     */
    public function createGame(string $playerName, int $balance, int $bet, int $numHands = 1): BlackJack
    {
        $blackJack = $this->createGameInstance();
        $blackJack->setPlayerName($playerName);
        $blackJack->setBalance($balance);
        $blackJack->setBet($bet);
        $blackJack->startGame($numHands);

        return $blackJack;
    }

    /**
     * Start a new round while keeping the player's name and balance.
     *
     * @param BlackJack $prevRound The previous round's game object.
     * @param int $bet The new bet amount for the round.
     * @param int $numHands Number of hands to play this round.
     * @return BlackJack New BlackJack game.
     */
    public function newRound(BlackJack $prevRound, int $bet, int $numHands = 1): BlackJack
    {
        $newGame = $this->createGameInstance();
        $newGame->setPlayerName($prevRound->getPlayerName());
        $newGame->setBalance($prevRound->getBalance());
        $newGame->setBet($bet);
        $newGame->startGame($numHands);

        return $newGame;
    }

    /**
     * Process a player's action (draw or stay) during the current round.
     *
     * @param BlackJack $game The current game state.
     * @param string $action The action to process: 'draw' or 'stay'.
     * @return BlackJack The updated game state.
     */
    public function processAction(BlackJack $game, string $action): BlackJack
    {
        if (!$game->isGameOver()) {
            if ($action === 'draw') {
                $game->drawForPlayer();
            } elseif ($action === 'stay') {
                $game->stay();
            }
        }
        return $game;
    }

    /**
     * Initialize a new game or continue from an existing one.
     *
     * @param BlackJack|null $existingGame An existing game instance or null.
     * @param string $playerName The player's name.
     * @param int $betAmount The amount to bet.
     * @param int $numHands Number of hands to play this round.
     * @return BlackJack A new or initialized game.
     */
    public function initOrCreate(?BlackJack $existingGame, string $playerName, int $betAmount, int $numHands = 1): BlackJack
    {
        $balance = $existingGame instanceof BlackJack ? $existingGame->getBalance() : 1000;
        return $this->createGame($playerName, $balance, $betAmount, $numHands);
    }
}
