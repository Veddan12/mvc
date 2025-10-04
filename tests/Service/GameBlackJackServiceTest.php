<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Card\BlackJack;
use App\Card\Deck;
use App\Card\Card;
use App\Card\PayoutBlackJack;
use App\Card\BlackJackRules;
use App\Service\GameBlackJackService;

/**
 * Unit tests for the GameBlackJackService class.
 */
class GameBlackJackServiceTest extends TestCase
{
    private GameBlackJackService $service;

    /**
     * Set up a fresh GameBlackJackService before each test.
     */
    protected function setUp(): void
    {
        $this->service = new GameBlackJackService();
    }


    /**
     * Test to init or create game with new name, bet and chosen number of hands.
     */
    public function testInitorCreate(): void
    {
        $game = $this->service->initOrCreate(null, 'Ved', 100, 1);

        $this->assertInstanceOf(BlackJack::class, $game);
        $this->assertEquals('Ved', $game->getPlayerName());
        $this->assertEquals(900, $game->getBalance()); // 1000 - 100 bet
        $this->assertEquals(100, $game->getBet());
    }


    /**
     * Test starting a new round: preserved name and updated balance.
     */
    public function testNewRound(): void
    {
        $game = $this->service->initOrCreate(null, 'Daj', 100, 1);
        $game->setBalance(600);

        $newGame = $this->service->newRound($game, 200, 2);

        $this->assertEquals('Daj', $newGame->getPlayerName());
        $this->assertEquals(200, $newGame->getBalance()); // 600 - 2x200 bet
        $this->assertEquals(200, $newGame->getBet());
    }


    /**
     * Test process action stay/draw.
     * Draw adds another card to the hand.
     * Stay moves to next hand/bank.
     */
    public function testProcessAction(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new Card('Spades', 'Ace'),
            new Card('Clubs', '7'),
            new Card('Hearts', '4'),
            new Card('Diamonds', '10'),
            new Card('Hearts', '2'),
            new Card('Hearts', '5'),
            new Card('Spades', '5'),
            new Card('Clubs', '3')
        );

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(800);

        $game->startGame(1);

        $handBefore = $game->getCurrentHand();
        $this->assertNotNull($handBefore, "Expected initial hand to exist.");
        $this->assertCount(2, $handBefore->getCards(), "Hand should start with 2 cards");

        $updatedGame = $this->service->processAction($game, 'draw');

        $hand = $updatedGame->getCurrentHand();
        $this->assertNotNull($hand, "Expected current hand to exist after draw.");
        $this->assertCount(3, $hand->getCards(), "After draw, hand should have 3 cards");

        $stayGame = $this->service->processAction($updatedGame, 'stay');
        $this->assertEquals(1, $stayGame->getCurrentHandIndex(), "After stay, current hand index moves to next hand");
    }
}
