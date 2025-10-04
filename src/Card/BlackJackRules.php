<?php

namespace App\Card;

/**
 * Class BlackJackRules.
 */
class BlackJackRules
{
    /**
     * Determine the result of the game based on player and bank hands.
     *
     * @param CardHand $hand The player's hand (single hand).
     * @param CardHand $bank The bank's hand.
     *
     * @return array<string, string>
     */
    public function determineResult(CardHand $hand, CardHand $bank, int $index): array
    {

        if ($this->hasBlackJackDirectly($hand)) {
            return [
                'result' => 'blackjack',
                'message' => "Hand " . ($index + 1) . ": Du vann med Blackjack!"
            ];
        }

        $playerTotal = $hand->getTotalBlackJack();
        $bankTotal = $bank->getTotalBlackJack();

        if ($playerTotal > 21) {
            return ['result' => 'loss', 'message' => "Hand " . ($index + 1) . ": Du förlorade!"];

        }

        if ($bankTotal > 21 || $playerTotal > $bankTotal) {
            return ['result' => 'win', 'message' => "Hand " . ($index + 1) . ": Du vann!"];
        }

        if ($bankTotal > $playerTotal) {
            return ['result' => 'loss', 'message' => "Hand " . ($index + 1) . ": Du förlorade!"];
        }

        return ['result' => 'draw', 'message' => "Hand " . ($index + 1) . ": Oavgjort!"];
    }

    /**
     * Checks if a hand has exactly 21 with 2 cards (Blackjack).
     */
    public function hasBlackJackDirectly(CardHand $hand): bool
    {
        return $hand->getTotalBlackJack() === 21 && $hand->getNrOfCards() === 2;
    }

    /**
     * Draw a card for bank from deck and add it to the bank's hand.
     */
    public function drawForBank(CardHand $bankHand, Deck $deck): void
    {
        while ($bankHand->getTotalBlackJack() < 17) {
            $card = $deck->drawCard();
            if ($card !== null) {
                $bankHand->addCard($card);
            }
        }
    }
}
