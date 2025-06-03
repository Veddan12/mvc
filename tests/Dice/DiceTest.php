<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }

    /**
     * Roll the dice and assert value is within bounds.
     */
    public function testRollDice(): void
    {
        $die = new Dice();
        $res = $die->roll();

        $res = $die->getValue();
        $this->assertTrue($res >= 1);
        $this->assertTrue($res <= 6);
    }

    public function testGetValueAfterRoll(): void
    {
        $die = new Dice();
        $rolled = $die->roll();
        $this->assertSame($rolled, $die->getValue());
    }

    public function testGetValueThrowsException(): void
    {
        $die = new Dice();
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Dice has not been rolled yet.");
        $die->getValue();
    }
}
