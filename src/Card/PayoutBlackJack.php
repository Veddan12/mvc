<?php

namespace App\Card;

/**
 * Class BlackJack payout.
 */
class PayoutBlackJack
{
    /**
     * Calculate the payout based on outcome and bet amount.
     *
     * @param string $outcome Game result: 'win', 'blackjack', 'draw', or 'loss'.
     * @param int $bet The player's original bet.
     *
     * @return int The payout amount to be added to the player's balance.
     */
    public function payout(string $outcome, int $bet): int
    {
        return match ($outcome) {
            'win' => $bet * 2,
            'blackjack' => (int)($bet * 2.5),
            'draw' => $bet,
            'loss' => 0,
            default => 0
        };
    }

    public function applyPayout(string $outcome, int $bet, int &$balance): void
    {
        $balance += $this->payout($outcome, $bet);
    }
}
