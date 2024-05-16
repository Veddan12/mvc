<?php

namespace App\Controller;

use App\Card\Deck;
use App\Card\CardJoker;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardGameControllerJson extends AbstractController
{
    #[Route("/api/deck", name: "api_sorted", methods: ["GET"])]
    public function getDeck(SessionInterface $session): JsonResponse
    {
        // Retrieve the deck from the session
        $deck = $session->get('deck');

        // $deck is an instance of the CardJoker class
        if (!$deck instanceof CardJoker) {
            // Initialize $deck as an instance of the CardJoker class
            $deck = new CardJoker(); // Use CardJoker instead of Card

            // Store the initialized deck in the session
            $session->set('deck', $deck);
        }

        // Return the deck as a JSON response
        return new JsonResponse($deck->getDeck());
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle", methods: ["POST"])]
    public function shuffleDeck(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck instanceof Deck) {
            $card = new CardJoker();
            $deck = new Deck($card->getDeck());

            $session->set('deck', $deck->getCards());
        }

        $deck->shuffle();

        $session->set('deck', $deck);

        return new JsonResponse($deck->getCards());
    }

    #[Route("/api/deck/draw", name: "api_draw_card", methods: ["POST"])]
    public function drawCard(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        $drawnCard = $deck->drawCard();

        if ($drawnCard !== null) {
            $session->set('deck', $deck);
            $remainingCardsCount = $deck->remainingCards();
            $session->set('remainingCardsCount', $remainingCardsCount);
            $session->set('remainingCards', $deck->getCards());

            // Return the drawn card, remaining cards count and deck as a JSON response
            return new JsonResponse([
                'drawnCard' => $drawnCard,
                'remainingCardsCount' => $remainingCardsCount,
                'deck' => $deck->getCards()
            ]);
        } else {
            return new JsonResponse(['error' => 'The deck is empty.'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route("/api/deck/draw/{number}", name: "api_draw_cards", methods: ["POST"])]
    public function drawMoreCards(int $number, SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');
        $drawnCards = $deck->drawMoreCards($number);

        $session->set('deck', $deck);
        $remainingCardsCount = $deck->remainingCards();
        $session->set('remainingCardsCount', $remainingCardsCount);
        $session->set('remainingCards', $deck->getCards());

        return new JsonResponse([
            'drawnCard' => $drawnCards,
            'remainingCardsCount' => $remainingCardsCount,
            'deck' => $deck->getCards()
        ]);
    }
}
