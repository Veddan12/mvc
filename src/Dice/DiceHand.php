<?php

namespace App\Dice;

use App\Dice\Dice;


/**
 * Class DiceHand
 *
 * Represents a hand of Dice objects.
 */
class DiceHand
{
    /** @var Dice[] Array holding Dice objects in the hand */
    private $hand = [];

    /**
     * Add a Dice object to the hand.
     *
     * @param Dice $die
     */
    public function add(Dice $die): void
    {
        $this->hand[] = $die;
    }

    /**
     * Roll all dice in the hand.
     */
    public function roll(): void
    {
        foreach ($this->hand as $die) {
            $die->roll();
        }
    }

    /**
     * Get the number of dice in the hand.
     *
     * @return int The count of dice.
     */
    public function getNumberDices(): int
    {
        return count($this->hand);
    }

    /**
     * Get numeric values from all dice in the hand.
     *
    * @return int[]
    */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getValue();
        }
        return $values;
    }

    /**
     * Get visual string representations from all dice in the hand.
     *
    * @return string[]
    */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getAsString();
        }
        return $values;
    }
}
