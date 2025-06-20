<?php

namespace App\Card;

/**
 * Class CardHand represents a hand of cards.
 */
class CardHand
{
    /** @var Card[] */
    private array $cards = [];

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Get total points of the hand.
     * Handle the Aces.
     * @return int
     */
    public function getTotal(): int
    {
        $sum = 0;
        $aces = [];

        foreach ($this->cards as $card) {
            if ($card->getValue() === 'Ace') {
                $aces[] = $card;
                continue;
            }
            $sum += $card->getNumericValue();
        }
        $numAces = count($aces);

        if ($numAces === 2) {
            // If 2 Aces — one counts as 14, one as 1
            $sum += 14 + 1;
        } elseif ($numAces === 1) {
            // One Ace — check the current sum
            $sum += ($sum + 14 <= 21) ? 14 : 1;
        } elseif ($numAces > 2) {
            // More than 2 Aces — treat one as 14, one as 1, rest as 1
            $sum += 14 + 1 + ($numAces - 2) * 1;
        }
        return $sum;
    }

    /**
     * Convert the hand to an array.
     *
     * @return array<int, array<string, string>>
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->cards as $card) {
            $array[] = $card->toArray();
        }
        return $array;
    }
}
