<?php

namespace App\Controller;

use App\Card\Deck;
use App\Card\CardJoker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller Card Game JSON API.
 */
class CardGameControllerJson extends AbstractController
{
    /**
     * Get the current deck of cards as a JSON response.
     *
     * @param SessionInterface $session
     * @return JsonResponse JSON array representation of the deck.
     */
    #[Route("/api/deck", name: "api_sorted", methods: ["GET"])]
    public function getDeck(SessionInterface $session): JsonResponse
    {
        $deck = $this->getOrInitDeck($session);

        return new JsonResponse($deck->getCardsAsArray());
    }

    /**
     * Shuffle the deck and update the session.
     *
     * @param SessionInterface $session
     * @return JsonResponse JSON array representation of the shuffled deck.
     */
    #[Route("/api/deck/shuffle", name: "api_shuffle", methods: ["POST"])]
    public function shuffleDeck(SessionInterface $session): JsonResponse
    {
        $deck = $this->getOrInitDeck($session);

        $deck->shuffle();

        $session->set('deck', $deck);

        return new JsonResponse($deck->getCardsAsArray());
    }

    /**
     * Draw a single card from the deck.
     *
     * @param SessionInterface $session
     * @return JsonResponse JSON with drawn card, remaining cards count, and deck array.
     */
    #[Route("/api/deck/draw", name: "api_draw_card", methods: ["POST"])]
    public function drawCard(SessionInterface $session): JsonResponse
    {
        $deck = $this->getOrInitDeck($session);

        $drawnCard = $deck->drawCard();

        if ($drawnCard === null) {
            return new JsonResponse(['error' => 'The deck is empty.'], Response::HTTP_BAD_REQUEST);
        }
        $session->set('deck', $deck);
        $remainingCardsCount = $deck->remainingCards();
        $session->set('remainingCardsCount', $remainingCardsCount);
        $session->set('remainingCards', $deck->getCardsAsArray());

        // Return the drawn card, remaining cards count and deck as a JSON response
        return new JsonResponse([
            'drawnCard' => $drawnCard->toArray(),
            'remainingCardsCount' => $remainingCardsCount,
            'deck' => $deck->getCardsAsArray()
        ]);
    }

    /**
     * Draw multiple cards from the deck.
     *
     * @param int $number Number of cards to draw.
     * @param SessionInterface $session
     * @return JsonResponse JSON with drawn cards array, remaining cards count, and deck array.
     */
    #[Route("/api/deck/draw/{number}", name: "api_draw_cards", methods: ["POST"])]
    public function drawMoreCards(int $number, SessionInterface $session): JsonResponse
    {
        $deck = $this->getOrInitDeck($session);

        $drawnCards = $deck->drawMoreCards($number);

        $session->set('deck', $deck);
        $remainingCardsCount = $deck->remainingCards();
        $session->set('remainingCardsCount', $remainingCardsCount);
        $session->set('remainingCards', $deck->getCards());

        $drawnCardsArray = array_map(function ($card) {
            return $card->toArray();
        }, $drawnCards);

        return new JsonResponse([
            'drawnCard' => $drawnCardsArray,
            'remainingCardsCount' => $remainingCardsCount,
            'deck' => $deck->getCardsAsArray()
        ]);
    }

    /** Helper method to get or init the deck */
    private function getOrInitDeck(SessionInterface $session): CardJoker
    {
        $deck = $session->get('deck');
        if (!$deck instanceof CardJoker) {
            $deck = new CardJoker();
            $session->set('deck', $deck);
        }
        return $deck;
    }
}
