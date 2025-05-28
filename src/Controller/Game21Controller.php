<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\Deck;
use App\Card\Game21;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Exception\RuntimeException;

class Game21Controller extends AbstractController
{
    #[Route("/game", name: "init_page")]
    public function landingPage(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/doc", name: "documentation")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/play", name: "play_game", methods: ["GET"])]
    public function play(SessionInterface $session): Response
    {
        //Init the game
        if (!$session->has('game21')) {
            $deck = new Deck();
            $deck->shuffle();
            $playerHand = new CardHand();
            $bankHand = new CardHand();
            $game = new Game21($deck, $playerHand, $bankHand);
            $session->set('game21', $game);
        }

        $game = $session->get('game21');

        if (!$game instanceof Game21) {
            throw new RuntimeException("Invalid game instance in session");
        }

        return $this->render('game/play.html.twig', [
            'game' => $game,
            'gameOver' => false,
            'result' => '',
        ]);
    }

    #[Route("/game/play", name: "play_game_post", methods: ["POST"])]
    public function handlePlayer(SessionInterface $session, Request $request): Response
    {
        $game = $session->get('game21');

        if (!$game instanceof Game21) {
            throw new RuntimeException("Invalid game instance in session");
        }

        // Player chose to draw or stay
        if ($request->request->get('action') === 'draw') {
            // Player draws a card
            $game->drawForPlayer();
        } elseif ($request->request->get('action') === 'stay') {
            // Player stays, start bank turn
            $game->drawForBank();
        }

        // If gameOver get the winner
        $gameOver = false;
        $result = '';
        if ($game->getPlayerHand()->getTotal() > 21) {
            $result = 'Du har över 21! Banken vinner!';
            $gameOver = true;
        } elseif ($game->getBankHand()->getTotal() >= 17 && $game->getBankHand()->getTotal() <= 21) {
            $result = $game->compareResults();
            $gameOver = true;
        }

        return $this->render('game/play.html.twig', [
            'game' => $game,
            'gameOver' => $gameOver,
            'result' => $result,
        ]);
    }

    #[Route("/game/restart", name: "game_restart")]
    public function restart(SessionInterface $session): Response
    {
        $session->remove('game21');
        return $this->redirectToRoute('play_game');
    }
}
