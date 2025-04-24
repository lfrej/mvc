<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JsonApi extends AbstractController
{
    #[Route("/api", name: "api")]
    public function jsonapi(): Response
    {
        $data = [
            [
                'route' => 'api/quote',
                'about' => 'Ett citat, dagens datum och tidsstämpel retuneras.',
            ],
            [
                'route' => 'api/deck',
                'about' => "Retunerar hela kortleken med värde och färg.",
            ],
            [
                'route' => 'api/deck/shuffle',
                'about' => "Retunerar en blandad kortlek",
            ],
            [
                'route' => 'api/deck/draw',
                'about' => "Drar ett kort från kortleken",
            ],
            [
                'route' => 'api/deck/draw:{num<\d+>}',
                'about' => "Drar flera kort från kortleken",
            ],
        ];

        return $this->render('api.html.twig', ['data' => $data]);
    }

    #[Route("/api/quote", name: "quote")]
    public function jsonquote(): Response
    {
        $quotes = array(
            "Det finns ingen bättre utbildning än motgångar.",
            "Sjömannen ber inte om medvind, han lär sig segla.",
            "Att misslyckas är bara ett annat sätt att lära sig hur man gör något rätt.",
            "Du behöver inte bli någon du inte är för att bli bättre än du var.",
            "Det är klokare att gå sin egen väg än att gå vilse i andras fotspår."
        );

        $randomquote = $quotes[array_rand($quotes)];

        $todaysdate = date("Y/m/d");
        $timestamp = time();

        $data = [
            'quote' => $randomquote,
            'date' => $todaysdate,
            'timestamp' => $timestamp
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function jsondeck(
        Request $request,
        SessionInterface $session
    ): Response {
        if ($session->get('card_deck')) {
            $deck = $session->get('card_deck');
            $deck->sort();

            $data = [
                "cardDeck" => $deck->getString(),
            ];

            $session->set("card_deck", $deck);
        } else {
            $data = [
                "message" => "Session är tom",
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }



    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function jsondeckshuffle(
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = $session->get('card_deck');
        $deck->shuffle();

        $data = [
            "cardDeck" => $deck->getString(),
        ];

        $session->set("card_deck", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['POST'])]
    public function jsondeckdraw(
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = $session->get('card_deck');

        $test = $deck->draw(1);
        $cardString = [];

        foreach ($test as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->count();

        $data = [
            "card" => $cardString,
            "cardsLeft" => $countDeck,
        ];

        $session->set("card_deck", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw:{num<\d+>}", name: "api_deck_draw_multiple", methods: ['POST'])]
    public function jsondeckdrawmultiple(
        int $num,
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = $session->get('card_deck');

        $test = $deck->draw($num);
        $cardString = [];

        foreach ($test as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->count();

        $data = [
            "card" => $cardString,
            "cardsLeft" => $countDeck,
        ];

        $session->set("card_deck", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
