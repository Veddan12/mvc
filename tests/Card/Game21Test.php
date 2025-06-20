<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Game21;
use App\Card\Deck;
use App\Card\CardHand;
use App\Card\Card;

/**
 * Test cases for class Game21.
 */
class Game21Test extends TestCase
{
    /**
     * Test init of the Game21 instance with mock dependencies.
     */
    public function testGame21Init(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand */
        $playerHand = $this->createMock(CardHand::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand */
        $bankHand = $this->createMock(CardHand::class);

        $game = new Game21($deck, $playerHand, $bankHand);

        $this->assertInstanceOf(Game21::class, $game);
    }

    /**
     * Test the getters for player and bank hands.
     */
    public function testGetPlayerHandAndBankHand(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand */
        $playerHand = $this->createMock(CardHand::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand */
        $bankHand = $this->createMock(CardHand::class);

        $game = new Game21($deck, $playerHand, $bankHand);

        $this->assertSame($playerHand, $game->getPlayerHand());
        $this->assertSame($bankHand, $game->getBankHand());
    }

    /**
     * Test drawing a card for the player:
     * - Add the card to player's hand,
     * - Update total,
     * - Check if game ends when player > 21,
     * - Verifies result message.
     */
    public function testDrawForPlayer(): void
    {
        $card = new Card("Hearts", "7");

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        $deck->method('drawCard')->willReturn($card);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand */
        $playerHand = $this->createMock(CardHand::class);
        $playerHand->expects($this->once())->method('addCard')->with($card);
        $playerHand->method('getTotal')->willReturn(22);
        $playerHand->method('getCards')->willReturn([$card]);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand */
        $bankHand = $this->createMock(CardHand::class);

        $game = new Game21($deck, $playerHand, $bankHand);
        $game->drawForPlayer();

        $this->assertCount(1, $game->getPlayerHand()->getCards());
        $this->assertSame($card, $game->getPlayerHand()->getCards()[0]);
        $this->assertTrue($game->isGameOver());
        $this->assertSame("Du har över 21! Du förlorade!", $game->getResult());
    }

    /**
     * Test drawing a card for the bank when the deck is empty:
     */
    public function testDrawForBankBreakIfDeckEmpty(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturn(null);

        $bankHand = new CardHand();

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand */
        $playerHand = $this->createMock(CardHand::class);

        $game = new Game21($deck, $playerHand, $bankHand);

        $game->drawForBank();

        $this->assertCount(0, $bankHand->getCards());
    }

    /**
     * Test drawing cards for the bank:
     * - Bank draws cards until 17 points.
     * - Verify total of bank hand matches expected value.
     */
    public function testDrawForBank(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new Card('hearts', '5'),
            new Card('spades', '6'),
            new Card('clubs', '6')
        );

        $game = new Game21($deck, new CardHand(), new CardHand());
        $game->drawForBank();

        $this->assertSame(17, $game->getBankHand()->getTotal());
    }

    /**
     * Test compare game results for different player and bank hand totals.
     */
    public function testCompareResults(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand1 */
        $playerHand1 = $this->createMock(CardHand::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand1 */
        $bankHand1 = $this->createMock(CardHand::class);

        $playerHand1->method('getTotal')->willReturn(23);
        $bankHand1->method('getTotal')->willReturn(18);
        $game = new Game21($deck, $playerHand1, $bankHand1);
        $this->assertSame("Du förlorade!", $game->compareResults());

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand2 */
        $playerHand2 = $this->createMock(CardHand::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand2 */
        $bankHand2 = $this->createMock(CardHand::class);

        $playerHand2->method('getTotal')->willReturn(18);
        $bankHand2->method('getTotal')->willReturn(18);
        $game = new Game21($deck, $playerHand2, $bankHand2);
        $this->assertSame("Du förlorade!", $game->compareResults());

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand3 */
        $playerHand3 = $this->createMock(CardHand::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand3 */
        $bankHand3 = $this->createMock(CardHand::class);

        $playerHand3->method('getTotal')->willReturn(20);
        $bankHand3->method('getTotal')->willReturn(17);
        $game = new Game21($deck, $playerHand3, $bankHand3);
        $this->assertSame("Du vann!", $game->compareResults());

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand4 */
        $playerHand4 = $this->createMock(CardHand::class);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand4 */
        $bankHand4 = $this->createMock(CardHand::class);

        $playerHand4->method('getTotal')->willReturn(18);
        $bankHand4->method('getTotal')->willReturn(23);
        $game = new Game21($deck, $playerHand4, $bankHand4);
        $this->assertSame("Du vann!", $game->compareResults());
    }
}
