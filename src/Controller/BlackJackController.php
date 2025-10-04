<?php

namespace App\Controller;

use App\Card\BlackJack;
use App\Service\GameBlackJackService;
use App\Service\GameBlackJackValidateService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Exception\RuntimeException;

/**
 * BlackJack Controller.
 */
final class BlackJackController extends AbstractController
{
    /**
     * Init the BlackJack game after bet is submitted.
     *
     * @param SessionInterface $session The current session.
     * @param Request $request The HTTP request object.
     *
     * @return Response Redirects to the play route or back to bet on error.
     */
    #[Route('/proj/bet', name: 'enter_bet_post', methods: ['POST'])]
    public function blackJackInit(SessionInterface $session, Request $request, GameBlackJackService $gameService, GameBlackJackValidateService $gameValidate): Response
    {
        // Extract and validate input from POST using service
        $input = $gameValidate->validateInput($request->request->all());
        if (is_string($input)) {
            $this->addFlash('danger', $input);
            return $this->redirectToRoute('enter_bet');
        }

        $playerName = $input['playerName'];
        $betAmount = (int) $input['betAmount'];
        $numHands = (int) $input['numHands'];

        // Preserve existing balance or init to 1000
        $existingGame = $session->get('blackJack');

        if (!$existingGame instanceof \App\Card\BlackJack) {
            $existingGame = null;
        }

        // Validate bet against current balance and number of hands
        $error = $gameValidate->validateBet($existingGame, $betAmount, $numHands);
        if ($error) {
            $this->addFlash('danger', 'Your bet exceeds the available balance.');
            return $this->redirectToRoute('enter_bet');
        }

        $blackJack = $gameService->initOrCreate($existingGame, $playerName, $betAmount, $numHands);

        // Store game in session
        $session->set('playerName', $playerName);
        $session->set('blackJack', $blackJack);

        return $this->redirectToRoute('proj_play');
    }

    /**
     * Handle the display of the blackJack play page.
     *
     * @param SessionInterface $session Session to store blackJack state.
     *
     * @return Response Renders the blackJack play Twig template.
     */
    #[Route("/proj/play", name: "proj_play", methods: ["GET"])]
    public function playBlackJack(SessionInterface $session): Response
    {
        $blackJack = $this->getBlackJackOrRedirect($session);
        if (!$blackJack) {
            return $this->redirectToRoute('enter_bet');
        }

        return $this->render('proj/play.html.twig', [
            'blackJack' => $blackJack,
            'gameOver' => false,
            'results' => $blackJack->getResults(),
            'playerHands' => $blackJack->getPlayerHands(),
            'currentHand' => $blackJack->getCurrentHand(),
            'bankHand' => $blackJack->getBankHand(),
            'balance' => $blackJack->getBalance(),
        ]);
    }

    /**
     * Handle the blackJack play.
     *
     * @param SessionInterface $session Session to store blackJack state.
     *
     * @return Response Renders the blackJack play Twig template.
     * @throws RuntimeException if the stored session blackJack object is invalid.
     */
    #[Route("/proj/play", name: "proj_play_post", methods: ["POST"])]
    public function handlePlayer(SessionInterface $session, Request $request, GameBlackJackService $gameService): Response
    {
        $blackJack = $session->get('blackJack');

        if (!$blackJack instanceof BlackJack) {
            throw new RuntimeException("Invalid blackJack instance in session");
        }

        // Get the action by the player (draw or stay)
        $action = $request->get("action");

        if (!is_string($action)) {
            $action = '';
        }

        // Process player action
        $blackJack = $gameService->processAction($blackJack, $action);

        $session->set('blackJack', $blackJack);

        return $this->render('proj/play.html.twig', [
            'blackJack' => $blackJack,
            'gameOver' => $blackJack->isGameOver(),
            'results' => $blackJack->getResults(),
            'playerHands' => $blackJack->getPlayerHands(),
            'currentHand' => $blackJack->getCurrentHand(),
            'bankHand' => $blackJack->getBankHand(),
            'balance' => $blackJack->getBalance(),
        ]);
    }

    /**
     * Handle request to start a new game round with a new bet.
     *
     * @param SessionInterface $session Current session.
     * @param Request $request POST request containing the bet.
     * @param GameBlackJackService $gameService Logic handler for game creation.
     *
     * @return Response Redirect to play or round view depending on bet validity.
     */
    #[Route("/proj/newround", name: "proj_new_round", methods: ['POST'])]
    public function newRound(SessionInterface $session, Request $request, GameBlackJackService $gameService, GameBlackJackValidateService $gameValidate): Response
    {
        $blackJack = $this->getBlackJackOrRedirect($session);
        if (!$blackJack) {
            return $this->redirectToRoute('enter_bet');
        }

        $betAmount = (int) $request->request->get('betAmount');
        $numHands = (int) $request->request->get('numHands', 1);

        // Validate bet
        $error = $gameValidate->validateBet($blackJack, $betAmount, $numHands);
        if ($error) {
            $this->addFlash('danger', $error);
            return $this->redirectToRoute('proj_round');
        }

        $newGame = $gameService->newRound($blackJack, $betAmount, $numHands);
        $session->set('blackJack', $newGame);

        return $this->redirectToRoute('proj_play');
    }

    /**
     * Display the round start page where the player enters a new bet.
     *
     * @param SessionInterface $session Current session.
     *
     * @return Response Renders the round view or redirects if no balance.
     */
    #[Route("/proj/round", name: "proj_round", methods: ['GET'])]
    public function round(SessionInterface $session): Response
    {
        $blackJack = $this->getBlackJackOrRedirect($session);
        if (!$blackJack) {
            return $this->redirectToRoute('enter_bet');
        }

        if ($blackJack->getBalance() <= 0) {
            return $this->redirectToRoute('proj_game_over');
        }

        return $this->render('proj/round.html.twig', [
            'blackJack' => $blackJack,
        ]);
    }

    /**
     * Helper to get a valid BlackJack game from session or return null.
     *
     * @param SessionInterface $session Current session.
     * @return BlackJack|null The game instance if valid, otherwise null.
     */
    private function getBlackJackOrRedirect(SessionInterface $session): ?BlackJack
    {
        $blackJack = $session->get('blackJack');
        if (!$blackJack instanceof BlackJack) {
            return null;
        }
        return $blackJack;
    }
}
