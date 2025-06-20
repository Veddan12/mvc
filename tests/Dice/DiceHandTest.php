<?php

namespace App\Tests\Dice;

use PHPUnit\Framework\TestCase;

use App\Dice\Dice;
use App\Dice\DiceHand;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
     /**
     * Test adding dice to the hand and counting them.
     */
    public function testAddAndGetNumberDices(): void
    {
        $hand = new DiceHand();
        $hand->add(new Dice());
        $hand->add(new Dice());

        $this->assertEquals(2, $hand->getNumberDices());
    }

    /**
     * Test dice roll and get values.
     */
    public function testRollAndGetValues(): void
    {
        $hand = new DiceHand();
        $die1 = new Dice();
        $die2 = new Dice();

        $hand->add($die1);
        $hand->add($die2);

        $hand->roll();
        $values = $hand->getValues();

        $this->assertCount(2, $values);
        foreach ($values as $value) {
            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual(6, $value);
        }
    }

    /**
     * Test getString.
     */
    public function testGetString(): void
    {
        $hand = new DiceHand();
        $die1 = new Dice();
        $die2 = new Dice();

        $hand->add($die1);
        $hand->add($die2);

        $hand->roll();
        $strings = $hand->getString();

        $this->assertCount(2, $strings);
        foreach ($strings as $str) {
            $this->assertNotEmpty($str);
        }
    }
}
