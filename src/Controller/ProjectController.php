<?php

namespace App\Controller;

/*use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Card\CardHand;*/
use App\Project\FiveCardDrawGame;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectController extends AbstractController
{
    #[Route("/proj", name: "project")]
    public function project(): Response
    {
        return $this->render('project/project.html.twig');
    }

    #[Route("/proj/about", name: "proj_about")]
    public function about(): Response
    {
        return $this->render('project/about.html.twig');
    }

    #[Route("/proj/start", name: "proj_start", methods: ['POST'])]
    public function start(
        Request $request,
        SessionInterface $session
    ): Response {
        $game = new FiveCardDrawGame();

        $playerName = $request->request->get('name');
        $session->set('name', $playerName);
        $session->set('game', $game);

        return $this->redirectToRoute('proj_play');
    }

    #[Route("/proj/play", name: "proj_play")]
    public function play(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");

        $round = $game->getRound();
        if ($round == "showDown") {
            return $this->redirectToRoute("proj_result");
        }

        $playerName = $session->get('name');
        $bank = $game->getBank();
        $pot = $bank->getTotalPot();
        $lastTwoActions = $game->getTwoLastActions();
        $playerResult = $game->evaluatePlayerHand();
        $opponentResult = $game->evaluateOpponentHand();
        $playerHand = $game->getPlayerHand();
        $opponentHand = $game->getOpponentHand();

        $data = [
            "round" => $round,
            "pot" => $pot,
            "lastTwoActions" => $lastTwoActions,
            "playerName" => $playerName,
            "playerResult" => $playerResult,
            "opponentResult" => $opponentResult,
            "playerHand" => $playerHand->getString(),
            "opponentHand" => $opponentHand->getString(),
        ];

        $session->set('game', $game);

        return $this->render('/project/play.html.twig', $data);
    }

    #[Route("/proj/play/restart", name: "proj_restart", methods: ['POST'])]
    public function restart(
        Request $request,
        SessionInterface $session
    ): Response {
        $chosen = $request->request->get('submit');

        if ($chosen === "New round") {
            $game = $session->get('game');
            $game->reset();
            $session->set('game', $game);

            return $this->redirectToRoute('proj_play');
        }
        $session->clear();

        return $this->redirectToRoute('project');
    }


    #[Route("/proj/result", name: "proj_result")]
    public function result(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $round = $game->getRound();
        $playerName = $session->get('name');
        $playerResult = $game->evaluatePlayerHand();
        $opponentResult = $game->evaluateOpponentHand();
        $playerHand = $game->getPlayerHand();
        $opponentHand = $game->getOpponentHand();
        $gameResult = $game->getResult();
        $opponentPot = $game->getOpponent()->getPot();
        $playerPot = $game->getPlayer()->getPot();

        $bank = $game->getBank();
        $totalPot = $bank->getTotalPot();

        $data = [
            "round" => $round,
            "playerName" => $playerName,
            "playerResult" => $playerResult,
            "opponentResult" => $opponentResult,
            "playerHand" => $playerHand->getString(),
            "opponentHand" => $opponentHand->getString(),
            "gameResult" => $gameResult,
            "opponentPot" => $opponentPot,
            "playerPot" => $playerPot,
            "totalPot" => $totalPot,
        ];

        $session->set('game', $game);

        return $this->render('/project/result.html.twig', $data);
    }
}
