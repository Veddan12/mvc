<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardHand;
use App\Card\Card;
use App\Card\BlackJackRules;

/**
 * Test cases for class Black Jack Rules.
 */
class BlackJackRulesTest extends TestCase
{
    /**
     * Test Determine Results when player loses.
     */
    public function testDetermineResultPlayerLoss(): void
    {
        $player = new CardHand();
        $player->addCard(new Card('Hearts', '10'));
        $player->addCard(new Card('Diamonds', '7'));

        $bank = new CardHand();
        $bank->addCard(new Card('Spades', '10'));
        $bank->addCard(new Card('Hearts', '9'));

        $rules = new BlackJackRules();
        $result = $rules->determineResult($player, $bank, 0);

        $this->assertEquals('loss', $result['result']);
        $this->assertStringContainsString('Du förlorade!', $result['message']);
    }

    /**
     * Test Determine Results when player wins.
     */
    public function testDetermineResultPlayerWins(): void
    {
        $player = new CardHand();
        $player->addCard(new Card('Hearts', '10'));
        $player->addCard(new Card('Diamonds', '9'));

        $bank = new CardHand();
        $bank->addCard(new Card('Spades', '10'));
        $bank->addCard(new Card('Hearts', '7'));

        $rules = new BlackJackRules();
        $result = $rules->determineResult($player, $bank, 0);

        $this->assertEquals('win', $result['result']);
        $this->assertStringContainsString('Du vann!', $result['message']);
    }

    /**
     * Test Determine Results when draw.
     */
    public function testDetermineResultDraw(): void
    {
        $player = new CardHand();
        $player->addCard(new Card('Hearts', '10'));
        $player->addCard(new Card('Diamonds', '9'));

        $bank = new CardHand();
        $bank->addCard(new Card('Spades', '10'));
        $bank->addCard(new Card('Hearts', '9'));

        $rules = new BlackJackRules();
        $result = $rules->determineResult($player, $bank, 0);

        $this->assertEquals('draw', $result['result']);
        $this->assertStringContainsString('Oavgjort!', $result['message']);
    }
}
