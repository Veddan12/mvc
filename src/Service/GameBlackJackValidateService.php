<?php

namespace App\Service;

use App\Card\BlackJack;

/**
 * Service class for BlackJack game handling the input from post.
 *
 * This class validates user input and bets.
 *
 */
class GameBlackJackValidateService
{
    /**
     * Validate the bet amount against game state.
     *
     * @param BlackJack|null $blackJack The current game instance.
     * @param int $betAmount The amount to bet.
     * @param int $numHands Number of hands to play in new round.
     * @return string|null Error message if validation fails, otherwise null.
     */
    public function validateBet(?BlackJack $blackJack, int $betAmount, int $numHands = 1): ?string
    {
        if ($betAmount <= 0) {
            return 'Invalid bet amount.';
        }

        // If no game exists yet, assume starting balance 1000
        $balance = $blackJack ? $blackJack->getBalance() : 1000;
        $totalBet = $betAmount * $numHands;
        if ($totalBet > $balance) {
            return 'Your bet exceeds your current balance.';
        }

        return null;
    }

    /**
     * Validate input from request.
     *
     * @param array<string, string> $data
     * @return array{playerName: string, betAmount: int, numHands: int}|string
     */
    public function validateInput(array $data): array|string
    {
        // Extract and validate input from POST
        $playerName = trim((string) ($data['playerName'] ?? ''));
        $betAmount = (int) ($data['betAmount'] ?? 0);
        $numHands = (int) ($data['numHands'] ?? 1);

        if ($playerName === '' || $betAmount <= 0 || $numHands < 1 || $numHands > 3) {
            return "Invalid input.";
        }

        return [
            'playerName' => $playerName,
            'betAmount' => $betAmount,
            'numHands' => $numHands,
        ];
    }
}
