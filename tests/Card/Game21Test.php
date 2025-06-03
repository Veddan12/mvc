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

    public function testDrawForPlayer(): void
    {
        $card = new Card("Hearts", "7");

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        $deck->method('drawCard')->willReturn($card);

        $playerHand = new CardHand();

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $bankHand */
        $bankHand = $this->createMock(CardHand::class);

        $game = new Game21($deck, $playerHand, $bankHand);


        $game->drawForPlayer();
        $this->assertCount(1, $game->getPlayerHand()->getCards());
        $this->assertSame($card, $game->getPlayerHand()->getCards()[0]);
    }

    public function testDrawForBank(): void
    {
        $card = new Card("Diamonds", "Queen");

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\Deck $deck */
        $deck = $this->createMock(Deck::class);

        $deck->method('drawCard')->willReturn($card);

        /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Card\CardHand $playerHand */
        $playerHand = $this->createMock(CardHand::class);

        $bankHand = new CardHand();

        $game = new Game21($deck, $playerHand, $bankHand);

        $game->drawForBank();

        $this->assertNotEmpty($game->getBankHand()->getCards());
        $this->assertGreaterThanOrEqual(17, $game->getBankHand()->getTotal());
    }

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
