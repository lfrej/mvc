<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiQuoteController extends AbstractController
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

    #[Route("/api/quote", name: "api_quote")]
    public function quote(): Response
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

        return new JsonResponse($data);
    }
}
