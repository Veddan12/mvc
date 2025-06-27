<?php

namespace App\Tests\Dice;

use PHPUnit\Framework\TestCase;
use App\Dice\DiceGraphic;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Test that the DiceGraphic class is a subclass of Dice.
     */
    public function testInheritanceFromDice(): void
    {
        $dice = new DiceGraphic();
        $this->assertInstanceOf(\App\Dice\Dice::class, $dice);
    }

    /**
     * Test that getAsString returns a valid Unicode symbol
     * representing the rolled dice value.
     */
    public function testGetAsString(): void
    {
        $dice = new DiceGraphic();
        $dice->roll();

        $representation = ['⚀', '⚁', '⚂', '⚃', '⚄', '⚅'];
        $symbol = $dice->getAsString();

        $this->assertContains($symbol, $representation);
    }
}
