<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiDeckController extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function deck(
        SessionInterface $session
    ): Response {

        if (empty($session->get('card_deck'))) {
            $data = [
                "message" => "Session är tom",
            ];

            return new JsonResponse($data);
        }

        $deck = $session->get('card_deck');
        $deck->sort();

        $data = [
            "cardDeck" => $deck->getString(),
        ];

        $session->set("card_deck", $deck);

        return new JsonResponse($data);
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function deckShuffle(
        SessionInterface $session
    ): Response {
        if (empty($session->get('card_deck'))) {
            $data = [
                "message" => "Session är tom",
            ];

            return new JsonResponse($data);
        }

        $deck = $session->get('card_deck');
        $deck->shuffle();

        $data = [
            "cardDeck" => $deck->getString(),
        ];

        $session->set("card_deck", $deck);

        return new JsonResponse($data);
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['POST'])]
    public function deckDraw(
        SessionInterface $session
    ): Response {
        if (empty($session->get('card_deck'))) {
            $data = [
                "message" => "Session är tom",
            ];

            return new JsonResponse($data);
        }

        $deck = $session->get('card_deck');

        $drawnCards = $deck->draw(1);
        $cardString = [];

        foreach ($drawnCards as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->getCount();

        $data = [
            "card" => $cardString,
            "cardsLeft" => $countDeck,
        ];

        $session->set("card_deck", $deck);

        return new JsonResponse($data);
    }

    #[Route("/api/deck/draw:{num<\d+>}", name: "api_deck_draw_multiple", methods: ['POST'])]
    public function deckDrawMultiple(
        int $num,
        SessionInterface $session
    ): Response {
        if (empty($session->get('card_deck'))) {
            $data = [
                "message" => "Session är tom",
            ];

            return new JsonResponse($data);
        }

        $deck = $session->get('card_deck');

        $drawnCards = $deck->draw($num);
        $cardString = [];

        foreach ($drawnCards as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->getCount();

        $data = [
            "card" => $cardString,
            "cardsLeft" => $countDeck,
        ];

        $session->set("card_deck", $deck);

        return new JsonResponse($data);
    }
}
