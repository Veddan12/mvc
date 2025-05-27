<?php

namespace App\Dice;

use LogicException;

class Dice
{
    /** @var int|null */
    protected ?int $value = null;

    public function __construct()
    {
        $this->value = null;
    }

    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    // public function getValue(): int
    // {
    //     return $this->value;
    // }
    public function getValue(): int
    {
        if ($this->value === null) {
            throw new LogicException("Dice has not been rolled yet.");
        }

        return $this->value;
    }

    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
