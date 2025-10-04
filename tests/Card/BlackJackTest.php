<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\BlackJack;
use App\Card\Deck;
use App\Card\Card;
use App\Card\PayoutBlackJack;
use App\Card\BlackJackRules;

/**
 * Test cases for class Black Jack.
 */
class BlackJackTest extends TestCase
{
    /**
     * Test init of the BlackJack instance with mock dependencies.
     */
    public function testBlackJackInit(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\BlackJackRules $rules */
        $rules = $this->createMock(BlackJackRules::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\PayoutBlackJack $payout */
        $payout = $this->createMock(PayoutBlackJack::class);

        $game = new BlackJack($deck, $rules, $payout);

        $this->assertInstanceOf(BlackJack::class, $game);
        $this->assertEquals([], $game->getPlayerHands());
    }

    public function testStartGameOneHand(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new Card('Spades', 'Ace'),
            new Card('Hearts', 'King'),
            new Card('Spades', '9'),
            new Card('Diamonds', '7')
        );

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(800);

        $game->startGame(1);

        $this->assertCount(1, $game->getPlayerHands());
        $this->assertCount(2, $game->getPlayerHands()[0]->getCards());
        $this->assertCount(2, $game->getBankHand()->getCards());
        // Check if bet is subtracted
        $this->assertEquals(700, $game->getBalance());
    }

    public function testStartGameThreeHands(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new Card('Spades', 'Ace'),
            new Card('Hearts', 'King'),
            new Card('Spades', '10'),
            new Card('Diamonds', '9'),
            new Card('Hearts', 'Ace'),
            new Card('Hearts', '2'),
            new Card('Spades', '3'),
            new Card('Diamonds', '7')
        );

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(700);

        $game->startGame(3);

        $this->assertCount(3, $game->getPlayerHands());
        foreach ($game->getPlayerHands() as $hand) {
            $this->assertCount(2, $hand->getCards());
        }
        $this->assertCount(2, $game->getBankHand()->getCards());
        $this->assertEquals(400, $game->getBalance());
    }

    public function testDrawForPlayer(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturn(new Card('Spades', '10'));

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(500);

        $game->startGame(1);
        $game->setPlayerName("Ved");

        // force bust -> move to next hand/bank
        $hand = $game->getCurrentHand();
        $this->assertNotNull($hand);
        $hand->addCard(new Card('Clubs', '10'));
        $hand->addCard(new Card('Diamonds', '10'));

        $game->drawForPlayer();

        $this->assertEquals(1, $game->getCurrentHandIndex());
        $this->assertEquals("Ved", $game->getPlayerName());
    }

    public function testCompareResults(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new Card('Spades', '10'),
            new Card('Hearts', '9'),
            new Card('Clubs', '7'),
            new Card('Diamonds', '8')
        );

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\BlackJackRules $rules */
        $rules = $this->createMock(BlackJackRules::class);
        $rules->method('determineResult')->willReturn([
            'result' => 'win',
            'message' => 'Du vann!'
        ]);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\PayoutBlackJack $payout */
        $payout = $this->createMock(PayoutBlackJack::class);
        $payout->method('payout')->with('win', 100)->willReturn(200);

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(500);

        $game->startGame(1);
        $game->compareResults();

        $this->assertTrue($game->isGameOver());
        $this->assertGreaterThanOrEqual(400, $game->getBalance());
        $this->assertStringContainsString('Du vann!', $game->getResults()[0]);
        $this->assertEquals(100, $game->getBet());
    }

    public function testPlayerHasBlackJack(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new Card('Spades', 'Ace'),
            new Card('Hearts', 'King'),
            new Card('Clubs', '8'),
            new Card('Diamonds', '9')
        );

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(800);

        $game->startGame(1);
        $game->compareResults();

        $this->assertStringContainsString("Du vann med Blackjack!", $game->getResults()[0]);
    }

    public function testDrawForBank(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new Card('Hearts', '8'),
            new Card('Clubs', '2'),
            new Card('Spades', '7'),
            new Card('Diamonds', '9'),
            new Card('Clubs', '10'),
            new Card('Hearts', '6')
        );

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(800);

        $game->startGame(1);
        $game->drawForBank();

        $this->assertGreaterThanOrEqual(17, $game->getBankHand()->getTotalBlackJack());
    }

    public function testStayMoveToNextHand(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturn(new Card('Hearts', '8'));

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(800);

        $game->startGame(2);

        $this->assertEquals(0, $game->getCurrentHandIndex());
        $game->stay();
        $this->assertEquals(1, $game->getCurrentHandIndex());
    }

    public function testStartGameInvalid(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturn(new Card('Hearts', '10'));

        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();

        $game = new BlackJack($deck, $rules, $payout);
        $game->setBet(100);
        $game->setBalance(800);

        $game->startGame(0); // invalid
        $this->assertCount(1, $game->getPlayerHands());

        $game->startGame(5); // too many
        $this->assertCount(1, $game->getPlayerHands());
    }

    public function testDrawForPlayerNoHand(): void
    {
        $deck = new Deck();
        $rules = new BlackJackRules();
        $payout = new PayoutBlackJack();
        $game = new BlackJack($deck, $rules, $payout);

        $game->drawForPlayer();  // should trigger the early return

        $this->assertNull($game->getCurrentHand());
    }
}
