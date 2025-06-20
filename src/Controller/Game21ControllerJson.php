<?php

namespace App\Controller;

use App\Card\Game21;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * API Controller for Game21.
 */
class Game21ControllerJson extends AbstractController
{
    /**
     * Returns the current state of the Game21 session.
     *
     * @param SessionInterface $session
     * @return JsonResponse The game state.
     */
    #[Route("/api/game", name: "api_game21", methods: ["GET"])]
    public function apiGame21(SessionInterface $session): Response
    {
        $game = $session->get('game21');

        if (!$game instanceof Game21) {
            return new JsonResponse([
                'error' => 'No active game found in session.'
            ], Response::HTTP_NOT_FOUND);
        }

        $playerHand = $game->getPlayerhand();
        $bankHand = $game->getBankHand();

        $data = [
            'player' => $playerHand->toArray(),
            'playerTotal' => $playerHand->getTotal(),
            'bank' => $bankHand->toArray(),
            'bankTotal' => $bankHand->getTotal(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
