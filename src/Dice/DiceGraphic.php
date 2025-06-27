<?php

namespace App\Dice;

/**
 * Class DiceGraphic
 *
 * Extends the Dice class to represent the dice value using Unicode dice face characters.
 */
class DiceGraphic extends Dice
{
    /**
     * Unicode representations of dice faces for values 1 to 6.
     *
     * @var string[]
     */
    /** @var string[] */
    private $representation = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    /**
     * DiceGraphic constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the graphical string representation of the current dice value.
     *
     * @return string Unicode character representing the dice face.
     */
    public function getAsString(): string
    {
        return $this->representation[$this->value - 1];
    }
}
