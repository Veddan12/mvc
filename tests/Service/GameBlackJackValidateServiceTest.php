<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Card\BlackJack;
use App\Service\GameBlackJackValidateService;

/**
 * Unit tests for the GameBlackJackValidateService class.
 */
class GameBlackJackValidateServiceTest extends TestCase
{
    private GameBlackJackValidateService $validateService;

    /**
     * Set up a fresh GameBlackJackValidateService before each test.
     */
    protected function setUp(): void
    {
        $this->validateService = new GameBlackJackValidateService();
    }

    /**
     * Test validating correct input data.
     * Should return an array with playerName, betAmount, and numHands.
     */
    public function testValidateInput(): void
    {
        $data = [
            'playerName' => 'Ved',
            'betAmount' => '100',
            'numHands' => '2',
        ];

        $res = $this->validateService->validateInput($data);

        $this->assertIsArray($res);
        $this->assertEquals('Ved', $res['playerName']);
        $this->assertEquals('100', $res['betAmount']);
        $this->assertEquals('2', $res['numHands']);
    }

    /**
     * Test validating input with invalid data.
     */
    public function testValidateInputInvalidData(): void
    {
        $data = [
            'playerName' => '',
            'betAmount' => '0',
            'numHands' => '5',
        ];

        $res = $this->validateService->validateInput($data);

        $this->assertIsString($res);
        $this->assertEquals('Invalid input.', $res);
    }

    /**
     * Test bet validation when bet exceeds the player's balance.
     */
    public function testValidateBetExceedsBalance(): void
    {
        /** @var \App\Card\BlackJack&\PHPUnit\Framework\MockObject\MockObject $mockGame */
        $mockGame = $this->createMock(BlackJack::class);
        $mockGame->method('getBalance')->willReturn(100);

        $error = $this->validateService->validateBet($mockGame, 200, 1);

        $this->assertEquals('Your bet exceeds your current balance.', $error);
    }

    /**
     * Test valid bet scenario.
     * Should return null (no error).
     */
    public function testValidateBet(): void
    {
        /** @var \App\Card\BlackJack&\PHPUnit\Framework\MockObject\MockObject $mockGame */
        $mockGame = $this->createMock(BlackJack::class);
        $mockGame->method('getBalance')->willReturn(500);

        $error = $this->validateService->validateBet($mockGame, 100, 2);

        $this->assertNull($error);
    }

    /**
     * Test bet validation when bet amount is zero.
     */
    public function testValidateBetZero(): void
    {
        /** @var \App\Card\BlackJack&\PHPUnit\Framework\MockObject\MockObject $mockGame */
        $mockGame = $this->createMock(BlackJack::class);
        $mockGame->method('getBalance')->willReturn(500);

        $error = $this->validateService->validateBet($mockGame, 0, 1);

        $this->assertEquals('Invalid bet amount.', $error);
    }
}
