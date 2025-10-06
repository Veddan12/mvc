<?php

namespace App\Controller;

use App\Card\BlackJack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Project Controller.
 */
final class ProjectController extends AbstractController
{
    /**
    * Renders the project index page.
    */
    #[Route('/proj', name: 'project_welcome')]
    public function proj(): Response
    {
        return $this->render('proj/welcome.html.twig');
    }

    /**
    * Renders the project starting game page.
    */
    #[Route('/proj/start', name: 'project')]
    public function startProj(SessionInterface $session): Response
    {
        $playerName = $session->get('playerName', null);

        return $this->render('proj/home.html.twig', [
            'playerName' => $playerName,
        ]);
    }

    /**
     * Renders the project about page.
     */
    #[Route("/proj/about", name: "project_about")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    /**
     * Show current session data.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/proj/session", name: "proj_show_session")]
    public function showSession(SessionInterface $session): Response
    {
        $sessionData = $session->all();

        return $this->render('proj/session.html.twig', [
            'sessionData' => $sessionData,
        ]);
    }

    /**
     * Clear session data and redirect.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/proj/session/delete", name: "proj_delete_session")]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear(); // Clear all session data

        // Add a flash message
        $this->addFlash('success', 'Session data has been deleted.');

        return $this->redirectToRoute('proj_show_session');
    }

    /**
     * Display the home page where player enters name and bet.
     *
     * @param SessionInterface $session The current session.
     *
     * @return Response The rendered home page.
     */
    #[Route('/proj/bet', name: 'enter_bet', methods: ['GET'])]
    public function blackJackBet(SessionInterface $session): Response
    {
        $playerName = $session->get('playerName', null);

        return $this->render('proj/home.html.twig', [
            'playerName' => $playerName,
        ]);
    }

    /**
     * Display the game over page when the player has no funds left.
     *
     * @param SessionInterface $session The current session.
     *
     * @return Response The rendered game over page.
     */
    #[Route("/proj/gameover", name: "proj_game_over")]
    public function gameOver(SessionInterface $session): Response
    {
        $blackJack = $session->get('blackJack');
        $balance = ($blackJack instanceof BlackJack) ? $blackJack->getBalance() : 0;

        return $this->render('proj/gameover.html.twig', [
            'balance' => $balance,
            'playerName' => $session->get('playerName'),
        ]);
    }

    /**
     * Handle cashing out: clears session and thanks the player.
     *
     * @param SessionInterface $session The current session.
     *
     * @return Response The rendered cashout page.
     */
    #[Route("/proj/cashout", name: "proj_cashout")]
    public function cashOut(SessionInterface $session): Response
    {
        $blackJack = $session->get('blackJack');
        $balance = ($blackJack instanceof BlackJack) ? $blackJack->getBalance() : 0;

        $playerName = $session->get('playerName', 'Player');
        $playerName = is_string($playerName) ? $playerName : 'Player';

        $session->clear();

        $this->addFlash('success', "$playerName cashed out with $balance credits. Thanks for playing!");

        return $this->render('proj/cashout.html.twig');
    }

    /**
     * Clear session data to start a new player.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/proj/new", name: "proj_new_player", methods: ["POST"])]
    public function newPlayer(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute('project');
    }
}
