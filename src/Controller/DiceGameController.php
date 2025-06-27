<?php

namespace App\Controller;

use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class DiceGameController extends AbstractController
{
    /**
     * Display the Pig game start page.
     *
     * @return Response
     */
    #[Route("/game/pig", name: "pig_start")]
    public function home(): Response
    {
        return $this->render('pig/home.html.twig');
    }

    /**
     * Test rolling a single dice.
     *
     * @return Response
     */
    #[Route("/game/pig/test/roll", name: "test_roll_dice")]
    public function testRollDice(): Response
    {
        $die = new Dice();

        return $this->render('pig/test/roll.html.twig', [
            "dice" => $die->roll(),
            "diceString" => $die->getAsString(),
        ]);
    }

    /**
     * Test rolling a number of DiceGraphic dice.
     *
     * @param int $num Number of dice to roll
     * @return Response
     */
    #[Route("/game/pig/test/roll/{num<\d+>}", name: "test_roll_num_dices")]
    public function testRollDices(int $num): Response
    {
        if ($num > 99) {
            throw new Exception("Can not roll more than 99 dices!");
        }

        $diceRoll = $this->rollDiceGraphics($num);

        return $this->render('pig/test/roll_many.html.twig', [
            "num_dices" => count($diceRoll),
            "diceRoll" => $diceRoll,
        ]);
    }

    /**
     * Helper to roll multiple DiceGraphic and return their string representations.
     *
     * @param int $num Number of dice
     * @return string[] Array of dice as strings
     */
    private function rollDiceGraphics(int $num): array
    {
        $diceRoll = [];
        for ($i = 1; $i <= $num; $i++) {
            $die = new DiceGraphic();
            $die->roll();
            $diceRoll[] = $die->getAsString();
        }
        return $diceRoll;
    }

    /**
     * Test a DiceHand with a mix of Dice and DiceGraphic objects.
     *
     * @param int $num Number of dice
     * @return Response
     */
    #[Route("/game/pig/test/dicehand/{num<\d+>}", name: "test_dicehand")]
    public function testDiceHand(int $num): Response
    {
        if ($num > 99) {
            throw new Exception("Can not roll more than 99 dices!");
        }

        $hand = new DiceHand();

        for ($i = 1; $i <= $num; $i++) {
            $die = ($i % 2 === 1) ? new DiceGraphic() : new Dice();
            $hand->add($die);
        }

        $hand->roll();

        return $this->render('pig/test/dicehand.html.twig', [
            "num_dices" => $hand->getNumberDices(),
            "diceRoll" => $hand->getString(),
        ]);
    }

    /**
     * Show the form to init a new Pig game.
     *
     * @return Response
     */
    #[Route("/game/pig/init", name: "pig_init_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('pig/init.html.twig');
    }

    /**
     * Handle form submission and init the game in session.
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/game/pig/init", name: "pig_init_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numDice = $request->request->get('num_dices');

        $hand = new DiceHand();
        for ($i = 1; $i <= $numDice; $i++) {
            $hand->add(new DiceGraphic());
        }
        $hand->roll();

        $session->set("pig_dicehand", $hand);
        $session->set("pig_dices", $numDice);
        $session->set("pig_round", 0);
        $session->set("pig_total", 0);

        return $this->redirectToRoute('pig_play');
    }

    /**
     * Display the current game.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/game/pig/play", name: "pig_play", methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        /** @var DiceHand $dicehand */
        $dicehand = $session->get("pig_dicehand");

        $data = [
            "pigDices" => $session->get("pig_dices"),
            "pigRound" => $session->get("pig_round"),
            "pigTotal" => $session->get("pig_total"),
            "diceValues" => $dicehand->getString()
        ];

        return $this->render('pig/play.html.twig', $data);
    }


    /**
     * Handle rolling the dice and update round score.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/game/pig/roll", name: "pig_roll", methods: ['POST'])]
    public function roll(
        SessionInterface $session
    ): Response {
        /** @var \App\Dice\DiceHand $hand */
        $hand = $session->get("pig_dicehand");
        $hand->roll();

        $roundTotal = $session->get("pig_round");
        $roundTotal = is_numeric($roundTotal) ? (int) $roundTotal : 0;
        $round = 0;
        $values = $hand->getValues();
        foreach ($values as $value) {
            if ($value === 1) {
                $round = 0;
                $roundTotal = 0;

                $this->addFlash('warning', 'You got a 1 and you lost the round points!');
                break;
            }
            $round += $value;
        }

        $session->set("pig_round", $roundTotal + $round);

        return $this->redirectToRoute('pig_play');
    }

    /**
     * Save the current round score to the total and reset the round.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/game/pig/save", name: "pig_save", methods: ['POST'])]
    public function save(
        SessionInterface $session
    ): Response {
        $roundTotal = (int) (is_numeric($session->get("pig_round")) ? $session->get("pig_round") : 0);
        $gameTotal = (int) (is_numeric($session->get("pig_total")) ? $session->get("pig_total") : 0);

        $session->set("pig_total", $roundTotal + $gameTotal);
        $session->set("pig_round", 0);

        $this->addFlash('notice', 'Your round was saved to the total!');

        return $this->redirectToRoute('pig_play');
    }
}
