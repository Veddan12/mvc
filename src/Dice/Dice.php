<?php

namespace App\Dice;

use LogicException;

/**
 * Class Dice
 *
 * Represents a single six-sided dice that can be rolled to get a value between 1 and 6.
 */
class Dice
{
    /**
     * Dice constructor.
     *
     * Initializes the dice with no value (not rolled yet).
     */
    /** @var int|null */
    protected ?int $value = null;

    /**
     * Dice constructor.
     *
     * Init the dice with no value.
     */
    public function __construct()
    {
        $this->value = null;
    }

    /**
     * Rolls the dice, random value between 1 and 6.
     *
     * @return int The value of the dice after rolling.
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    /**
     * Get the current value of the dice.
     *
     * @throws LogicException if the dice has not been rolled yet.
     * @return int The face value of the dice.
     */
    public function getValue(): int
    {
        if ($this->value === null) {
            throw new LogicException("Dice has not been rolled yet.");
        }

        return $this->value;
    }

    /**
     * Get a string representation of the dice's current value.
     *
     * @return string
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
