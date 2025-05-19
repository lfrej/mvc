<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
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
            [
                'route' => 'api/game',
                'about' => "Se ställning i spelet 21",
            ],
            [
                'route' => 'api/library/books',
                'about' => "Se alla böcker",
            ],
                        [
                'route' => 'api/library/books/{isbn}',
                'about' => "Se en bok med ISBN 9781408855652",
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
        SessionInterface $session
    ): Response {

        if (empty($session->get('card_deck'))) {
            $data = [
                "message" => "Session är tom",
            ];
        }

        if ($session->get('card_deck')) {
            $deck = $session->get('card_deck');
            $deck->sort();

            $data = [
                "cardDeck" => $deck->getString(),
            ];

            $session->set("card_deck", $deck);
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function jsondeckshuffle(
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
        SessionInterface $session
    ): Response {
        $deck = $session->get('card_deck');

        $test = $deck->draw(1);
        $cardString = [];

        foreach ($test as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->getCount();

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
        SessionInterface $session
    ): Response {
        $deck = $session->get('card_deck');

        $test = $deck->draw($num);
        $cardString = [];

        foreach ($test as $card) {
            $cardString[] = $card->getAsString();
        }

        $countDeck = $deck->getCount();

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

    #[Route("/api/game", name: "api_game", methods: ['GET'])]
    public function game(
        SessionInterface $session
    ): Response {
        $game = $session->get('game');

        $player = $game->getPlayer();
        $bank = $game->getBank();

        $playerData = [
            "Korthand" => $player->getString(),
            "Poäng" => $player->getSum(),
        ];

        $bankData = [
            "Korthand" => $bank->getString(),
            "Poäng" => $bank->getSum(),
        ];

        $result = $game->getResult();
        if ($player->getCount() == 0 || $bank->getCount() == 0) {
            $result = "Spelet är inte klart";
        }

        $data = [
            "Spelaren" => $playerData,
            "Banken" => $bankData,
            "Ställning" => $result,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/library/books", name: "api_library", methods: ['GET'])]
    public function library(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $test = [];
        foreach ($books as $book) {
            $test[] = [
                'Title' => $book->getTitle(),
                'ISBN' => $book->getIsbn(),
                'Author' => $book->getAuthor(),
            ];
        };

        $data = [
            "Books" => $test,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/library/books/{isbn}", name: "api_library_isbn", methods: ['GET'])]
    public function libraryIsbn(
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);


        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for isbn '.$isbn
            );
        }

        $data = [
            'Title' => $book->getTitle(),
            'ISBN' => $book->getIsbn(),
            'Author' => $book->getAuthor(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
