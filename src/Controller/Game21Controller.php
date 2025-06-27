<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\Deck;
use App\Card\Game21;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Exception\RuntimeException;

/**
 * Controller to handle Game21 logic.
 */
class Game21Controller extends AbstractController
{
    /**
     * Landing page of the game.
     *
     * @return Response Renders the home page Twig template.
     */
    #[Route("/game", name: "init_page")]
    public function landingPage(): Response
    {
        return $this->render('game/home.html.twig');
    }

    /**
     * Documentation page for the game.
     *
     * @return Response Renders the documentation Twig template.
     */
    #[Route("/game/doc", name: "documentation")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    /**
     * Handles the display of the game play page.
     *
     * @param SessionInterface $session Session to store game state.
     *
     * @return Response Renders the game play Twig template.
     * @throws RuntimeException if the stored session game object is invalid.
     */
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

        // Retrieve game object from session
        $game = $session->get('game21');

        // Validate the object type
        if (!$game instanceof Game21) {
            throw new RuntimeException("Invalid game instance in session");
        }

        return $this->render('game/play.html.twig', [
            'game' => $game,
            'gameOver' => false,
            'result' => '',
        ]);
    }

    /**
     * Handles the game play.
     *
     * @param SessionInterface $session Session to store game state.
     *
     * @return Response Renders the game play Twig template.
     * @throws RuntimeException if the stored session game object is invalid.
     */
    #[Route("/game/play", name: "play_game_post", methods: ["POST"])]
    public function handlePlayer(SessionInterface $session, Request $request): Response
    {
        $game = $session->get('game21');

        if (!$game instanceof Game21) {
            throw new RuntimeException("Invalid game instance in session");
        }

        // Get the action by the player (draw or stay)
        $action = $request->request->get('action');

        // Process player action
        if (!$game->isGameOver()) {
            if ($action === 'draw') {
                $game->drawForPlayer();
            } elseif ($action === 'stay') {
                $game->drawForBank();
            }
        }

        $session->set('game21', $game);

        return $this->render('game/play.html.twig', [
            'game' => $game,
            'gameOver' => $game->isGameOver(),
            'result' => $game->getResult(),
        ]);
    }

    /**
     * Restart the game route.
     *
     * @param SessionInterface $session Session to store game state.
     *
     * @return Response Redirects to the game play route.
     */
    #[Route("/game/restart", name: "game_restart")]
    public function restart(SessionInterface $session): Response
    {
        $session->remove('game21');
        return $this->redirectToRoute('play_game');
    }
}
