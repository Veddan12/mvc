<?php

namespace App\Card;

class Deck
{
    private $cards;
    private $remainingCards;

    public function __construct(array $cards)
    {
        $this->cards = $cards;
        $this->remainingCards = count($cards);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function drawCard(): ?array
    {
        if ($this->remainingCards > 0) {
            // Get a random index for the card to draw
            $randomIndex = array_rand($this->cards);
            $drawnCard = $this->cards[$randomIndex];

            // Remove the drawn card from the deck
            array_splice($this->cards, $randomIndex, 1);
            $this->remainingCards--;
            return $drawnCard;
        } else {
            return null;
        }
    }

    public function drawMoreCards(int $num)
    {
        $drawnCards = [];

        for ($i = 0; $i < $num; $i++) {
            $drawnCard = $this->drawCard();
            if ($drawnCard !== null) {
                $drawnCards[] = $drawnCard;
            } else {
                return $drawnCards;
            }
        }

        return $drawnCards;
    }

    public function remainingCards(): int
    {
        return $this->remainingCards;
    }
}
