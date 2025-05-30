<?php

namespace App\Controller;

// use App\Card\Card;
use App\Card\Deck;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route("/game", name: "init_page")]
    public function landingPage(SessionInterface $session): Response
    {
        // Get all session data
        $sessionData = $session->all();
        // Print out
        dump($sessionData);

        return $this->render('card/init.html.twig', [
            'sessionData' => $sessionData
        ]);
    }

    #[Route("/card/session", name: "show_session")]
    public function showSession(SessionInterface $session): Response
    {
        $sessionData = $session->all();

        return $this->render('card/session.html.twig', [
            'sessionData' => $sessionData,
        ]);
    }

    #[Route("/card/session/delete", name: "delete_session")]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear(); // Clear all session data

        // Add a flash message
        $this->addFlash('success', 'Session data has been deleted.');

        return $this->redirectToRoute('show_session');
    }

    #[Route("/card", name: "card_start")]
    public function home(SessionInterface $session): Response
    {
        $deck = new Deck();
        $session->set('deck', $deck);
        return $this->render('card/home.html.twig', [
            'deck' => $deck->getCards()
        ]);
    }

    #[Route("/card/deck/", name: "sorted_deck")]
    public function sorted(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof Deck) {
            $deck = new Deck();
            $session->set('deck', $deck);
        }
        return $this->render('card/deck/sorted.html.twig', [
            'deck' => $deck->getCards()
        ]);
    }

    #[Route("/card/deck/shuffle", name: "shuffle_deck")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        if (!$deck instanceof Deck) {
            $deck = new Deck();
            $session->set('deck', $deck);
        }
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->render('card/deck/shuffle.html.twig', [
            'deck' => $deck->getCards()
        ]);
    }

    #[Route("/card/deck/draw", name: "draw_card")]
    public function drawCard(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        if (!$deck instanceof Deck) {
            $deck = new Deck();
            $session->set('deck', $deck);
        }
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

    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_cards")]
    public function drawCards(int $num, SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof Deck) {
            $deck = new Deck();
            $session->set('deck', $deck);
        }
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

    #[Route("/card/deck/empty", name: "empty_deck")]
    public function emptyDeck(): Response
    {
        return $this->render('card/deck/empty_deck.html.twig');
    }
}
