<?php

namespace App\Controller;

use App\Card\Deck;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller to handle card game.
 */
class CardGameController extends AbstractController
{
    /**
     * Render the home page of the card game.
     *
     * @return Response
     */
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    /**
     * Show current session data.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/card/session", name: "show_session")]
    public function showSession(SessionInterface $session): Response
    {
        $sessionData = $session->all();

        return $this->render('card/session.html.twig', [
            'sessionData' => $sessionData,
        ]);
    }

    /**
     * Clear session data and redirect.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/card/session/delete", name: "delete_session")]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear(); // Clear all session data

        // Add a flash message
        $this->addFlash('success', 'Session data has been deleted.');

        return $this->redirectToRoute('show_session');
    }

    /**
     * Display the deck in sorted order.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/card/deck/", name: "sorted_deck")]
    public function sorted(SessionInterface $session): Response
    {
        $deck = $this->getOrInitDeck($session);

        return $this->render('card/deck/sorted.html.twig', [
            'deck' => $deck->getCards()
        ]);
    }

    /**
     * Shuffle the deck.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/card/deck/shuffle", name: "shuffle_deck")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = $this->getOrInitDeck($session);
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->render('card/deck/shuffle.html.twig', [
            'deck' => $deck->getCards()
        ]);
    }

    /**
     * Draw a single card from the deck.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/card/deck/draw", name: "draw_card")]
    public function drawCard(SessionInterface $session): Response
    {
        $deck = $this->getOrInitDeck($session);
        $drawnCard = $deck->drawCard();
        if ($drawnCard !== null) {
            $remainingCardsCount = $deck->remainingCards();

            $session->set('drawnCard', $drawnCard);
            $session->set('remainingCardsCount', $remainingCardsCount);
            $session->set('remainingCards', $deck->getCards());

            return $this->render('card/deck/draw.html.twig', [
                'drawnCard' => $drawnCard,
                'remainingCardsCount' => $remainingCardsCount,
                'remainingCards' =>  $deck->getCards()
            ]);
        }
        // If no more cards in the deck
        // Redirect to empty deck page
        return $this->redirectToRoute('empty_deck');
    }

    /**
     * Draw multiple cards from the deck.
     *
     * @param int $num Number of cards to draw
     * @param SessionInterface $session
     * @return Response
     */
    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_cards")]
    public function drawCards(int $num, SessionInterface $session): Response
    {
        $deck = $this->getOrInitDeck($session);
        // Draw the specified number of cards from the deck
        $drawnCards = [];
        for ($i = 0; $i < $num; $i++) {
            $drawnCard = $deck->drawCard();
            if ($drawnCard !== null) {
                $drawnCards[] = $drawnCard;
                continue;
            }
            return $this->redirectToRoute('empty_deck');
        }

        $remainingCardsCount = $deck->remainingCards();

        $session->set('drawnCards', $drawnCards);
        $session->set('remainingCardsCount', $remainingCardsCount);
        $session->set('remainingCards', $deck->getCards());

        $session->set('deck', $deck);

        return $this->render('card/deck/draw_few.html.twig', [
            'drawnCards' => $drawnCards,
            'remainingCards' =>  $deck->getCards(),
            'remainingCardsCount' => $deck->remainingCards()
        ]);
    }

    /**
     * Render a page that the deck is empty.
     *
     * @return Response
     */
    #[Route("/card/deck/empty", name: "empty_deck")]
    public function emptyDeck(): Response
    {
        return $this->render('card/deck/empty_deck.html.twig');
    }

    /** Helper method to get or init the deck */
    private function getOrInitDeck(SessionInterface $session): Deck
    {
        $deck = $session->get('deck');

        if (!$deck instanceof Deck) {
            $deck = new Deck();
            $session->set('deck', $deck);
        }

        return $deck;
    }
}
