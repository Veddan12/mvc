<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DiceGameControllerTest
 */
class DiceGameControllerTest extends WebTestCase
{
    /**
     * Test that the Pig game home page.
     */
    public function testHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Pig game');
    }

    /**
     * Test that the dice roll test page.
     */
    public function testTestRollDice(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/test/roll');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Roll a dice');
    }

    /**
     * Test rolling multiple dices
     * and that the dice result elements exist in the page.
     */
    public function testRollDices(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/test/roll/5');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.die');
    }

    /**
     * Test rolling a dice hand.
     */
    public function testRollDiceHand(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/test/dicehand/4');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul > li');
    }

    /**
     * Test the Pig game init GET page loads
     * and contains a form.
     */
    public function testInit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/init');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test submitting the Pig game init form
     * redirects to the game play route.
     */
    public function testInitPost(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/pig/init');

        $form = $crawler->selectButton('Start playing')->form();
        $form['num_dices'] = '2';

        $client->submit($form);
        $client->followRedirect();

        $this->assertRouteSame('pig_play');
    }

    /**
     * Test that rolling more than 99 dices throws an exception.
     */
    public function testRollDicesThrowsExceptionWhenTooMany(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can not roll more than 99 dices!');

        $client->request('GET', '/game/pig/test/roll/100');
    }

    /**
     * Test that rolling a dice hand with more than 99 dices throws an exception.
     */
    public function testRollDiceHandThrowsExceptionWhenTooMany(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can not roll more than 99 dices!');

        $client->request('GET', '/game/pig/test/dicehand/100');
    }
}
