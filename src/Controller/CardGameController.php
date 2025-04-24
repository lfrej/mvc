<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function me(): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route("/session", name: "session")]
    public function getSession(SessionInterface $session): Response
    {
        $sessionData = $session->all();

        $data = [
            'session' => $sessionData,
        ];

        return $this->render('/card/session.html.twig', $data);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();

        $this->addFlash(
            'warning',
            'Session är raderad'
        );

        $sessionData = $session->all();

        $data = [
            'session' => $sessionData,
        ];

        return $this->render('/card/session.html.twig', $data);
    }

    #[Route("/card/deck", name: "deck")]
    public function deck(
        Request $request,
        SessionInterface $session
    ): Response {
        if ($session->get('card_deck') == null) {
            $deck = new DeckOfCards();
        } else {
            $deck = $session->get('card_deck');

            $deck->sort();
        }

        $data = [
            "cardDeck" => $deck->getString(),
        ];

        $session->set("card_deck", $deck);

        return $this->render('/card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck_shuffle")]
    public function deckshuffle(
        Request $request,
        SessionInterface $session
    ): Response {

        $deck = new DeckOfCards();

        $deck->shuffle();

        $data = [
            "deckShuffle" => $deck->getString(),
        ];

        $session->set("card_deck", $deck);

        return $this->render('/card/deckshuffle.html.twig', $data);
    }


    #[Route("/card/deck/draw", name: "draw_card")]
    public function drawcard(
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = $session->get('card_deck');

        $drawnCards = $deck->draw(1);

        $cardString = [];
        foreach ($drawnCards as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->count();

        $data = [
            "card" => $cardString,
            "count" => $countDeck,
        ];

        $session->set("card_deck", $deck);

        return $this->render('/card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw:{num<\d+>}", name: "draw_card_num")]
    public function drawCardNum(
        int $num,
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = $session->get('card_deck');

        $drawnCards = $deck->draw($num);

        $cardString = [];
        foreach ($drawnCards as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->count();

        $data = [
            "card" => $cardString,
            "count" => $countDeck,
        ];

        $session->set("card_deck", $deck);

        return $this->render('/card/draw_many.html.twig', $data);
    }
}
