<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiProjectController extends AbstractController
{
    #[Route("/api/proj", name: "proj_api", methods: ['GET'])]
    public function game(): Response
    {
        $data = [
            [
                'route' => 'api/proj/player',
                'about' => 'Visar spelarens spel. Hand, senaste drag och nuvarande vunna pengar',
            ],
            [
                'route' => 'api/proj/opponent',
                'about' => "Visar motståndarens spel. Hand, senaste drag och nuvarande vunna pengar",
            ],
            [
                'route' => 'api/proj/bank',
                'about' => "Visar spelets nuvarande pott och bet",
            ],
            [
                'route' => 'api/proj/draw',
                'about' => "Byt ett kort i spelarens korthand",
            ],
            [
                'route' => 'api/proj/result',
                'about' => "Se resultat spel",
            ],
        ];

        return $this->render('project/api.html.twig', ['data' => $data]);
    }

    #[Route("api/proj/player", name: "api_player", methods: ['GET'])]
    public function apiPlayer(
        SessionInterface $session
    ): Response {
        if (empty($session->get('game'))) {
            $data = [
                "meddelande" => "Det finns inget spel",
            ];

            return new JsonResponse($data);
        }

        $game = $session->get("game");

        $playerHand = $game->getPlayerHand();
        $evaluatedPlayer = $game->evaluatePlayerHand();
        $lastActionPlayer = $game->getLastPlayerAction();
        $potPlayer = $game->getPlayer()->getPot();

        $data = [
            "hand" => $playerHand->getString(),
            "pokerHand" => $evaluatedPlayer,
            "lastAction" => $lastActionPlayer,
            "pot" => $potPlayer,
        ];

        return new JsonResponse($data);
    }

    #[Route("api/proj/opponent", name: "api_opponent", methods: ['GET'])]
    public function apiOpponent(
        SessionInterface $session
    ): Response {
        if (empty($session->get('game'))) {
            $data = [
                "meddelande" => "Det finns inget spel",
            ];

            return new JsonResponse($data);
        }

        $game = $session->get("game");

        $opponentHand = $game->getOpponentHand();
        $evaluatedOpponent = $game->evaluateOpponentHand();
        $lastActionOpponent = $game->getLastOpponentAction();
        $potOpponent = $game->getOpponent()->getPot();

        $data = [
            "hand" => $opponentHand->getString(),
            "pokerHand" => $evaluatedOpponent,
            "lastAction" => $lastActionOpponent,
            "pot" => $potOpponent,
        ];

        return new JsonResponse($data);
    }

    #[Route("api/proj/bank", name: "api_bank", methods: ['GET'])]
    public function apiBank(
        SessionInterface $session
    ): Response {
        if (empty($session->get('game'))) {
            $data = [
                "meddelande" => "Det finns inget spel",
            ];

            return new JsonResponse($data);
        }

        $game = $session->get("game");

        $bank = $game->getBank();
        $currentPot = $bank->getTotalPot();
        $currentBet = $bank->getCurrentBet();

        $data = [
            "pot" => $currentPot,
            "bet" => $currentBet,
        ];

        return new JsonResponse($data);
    }

    #[Route("api/proj/draw", name: "api_draw", methods: ['POST'])]
    public function apiDraw(
        SessionInterface $session
    ): Response {
        if (empty($session->get('game'))) {
            $data = [
                "meddelande" => "Det finns inget spel",
            ];

            return new JsonResponse($data);
        }

        $game = $session->get('game');

        $game->drawPlayer([1]);

        $playerHand = $game->getPlayerHand();
        $evaluatedPlayerHand = $game->evaluatePlayerHand();

        $data = [
            "hand" => $playerHand->getString(),
            "pokerHand" => $evaluatedPlayerHand,
        ];

        return new JsonResponse($data);
    }

    #[Route("api/proj/result", name: "api_result", methods: ['GET'])]
    public function apiResult(
        SessionInterface $session
    ): Response {
        if (empty($session->get('game'))) {
            $data = [
                "meddelande" => "Det finns inget spel",
            ];

            return new JsonResponse($data);
        }

        $game = $session->get('game');
        $round = $game->getRound();
        $playerName = $session->get("name");
        $totalPot = $game->getBank()->getTotalPot();

        if ($round === "showDown") {
            $result = $game->getResult();

            $resultString = $playerName . "They got even! They both got " . $totalPot / 2 . " SEK";
            if ($result == "Player") {
                $resultString = $playerName . " won! They got " . $totalPot . " SEK";
            } elseif ($result == "Opponent") {
                $resultString = "Opponent won! They got " . $totalPot . " SEK";
            }

            $data = [
                "resultat" => $resultString,
            ];
            return new JsonResponse($data);
        }

        $data = [
                "meddelande" => "Spelet är inte klart",
            ];

        return new JsonResponse($data);
    }

}
