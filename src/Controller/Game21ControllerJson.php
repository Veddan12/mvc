<?php

namespace App\Controller;

use App\Card\Game21;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Game21ControllerJson extends AbstractController
{
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
