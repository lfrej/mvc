<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeTwig extends AbstractController
{
    #[Route("/", name: "me")]
    public function me(): Response
    {
        return $this->render('me.html.twig');
    }
    #[Route("/home", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }
    #[Route("/lucky", name: "lucky")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky.html.twig', $data);
    }

    #[Route("/api", name: "api")]
    public function api(): Response
    {
        $data = [
            [
                'route' => 'me',
                'path' => "/"
            ],
            [
                'route' => 'home',
                'path' => "/home"
            ],
            [
                'route' => 'about',
                'path' => "/about"
            ],
            [
                'route' => 'report',
                'path' => "/report"
            ],
            [
                'route' => 'lucky',
                'path' => "/lucky"
            ],
            [
                'route' => 'api',
                'path' => "/api"
            ]
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote", name: "quote")]
    public function quote(): Response
    {
        $quotes = Array(
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
}