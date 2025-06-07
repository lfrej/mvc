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

class ProjectActionsController extends AbstractController
{
    #[Route("/proj/play/check", name: "proj_check")]
    public function check(
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        $name = $session->get('name');

        $game->getPlayerAction()->check();

        $this->addFlash(
            'notice',
            $name . ' checked'
        );

        $opponentAction = $game->decideAction();

        $lastAction = $game->getLastOpponentAction();

        $this->addFlash(
            'notice',
            "Opponent " . $lastAction . " " . $opponentAction
        );

        $game->handleRound();

        return $this->redirectToRoute("proj_play");
    }

    #[Route("/proj/play/bet", name: "proj_bet", methods: ['POST'])]
    public function bet(
        Request $request,
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        $name = $session->get('name');

        $bet = $request->request->get('bet');
        $result = $game->getPlayerAction()->bet($bet ?? null);

        $this->addFlash(
            'notice',
            $name . ' betted' . $result
        );

        $opponentAction = $game->decideAction();

        $lastAction = $game->getLastOpponentAction();

        $this->addFlash(
            'notice',
            "Opponent " . $lastAction . " " . $opponentAction
        );

        $game->handleRound();

        return $this->redirectToRoute('proj_play');
    }

    #[Route("/proj/play/raise", name: "proj_raise", methods: ['POST'])]
    public function raise(
        Request $request,
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        $name = $session->get('name');

        $raise = $request->request->get('raise');

        $result = $game->getPlayerAction()->raise($raise ?? null);

        $this->addFlash(
            'notice',
            $name . ' raised ' . $result
        );

        $opponentAction = $game->decideAction();

        $lastAction = $game->getLastOpponentAction();

        $this->addFlash(
            'notice',
            "Opponent " . $lastAction . " " . $opponentAction
        );

        $game->handleRound();

        return $this->redirectToRoute('proj_play');
    }

    #[Route("/proj/play/call", name: "proj_call")]
    public function call(
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        $name = $session->get('name');

        $called = $game->getPlayerAction()->call();


        $this->addFlash(
            'notice',
            $name . ' called ' . $called
        );

        $game->handleRound();

        return $this->redirectToRoute('proj_play');
    }

    #[Route("/proj/play/fold", name: "proj_fold")]
    public function fold(
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        $game->getPlayerAction()->fold();

        $game->handleTurn();
        $game->handleRound();

        return $this->redirectToRoute('proj_result');
    }

    #[Route("/proj/play/draw", name: "proj_draw", methods: ['POST'])]
    public function draw(
        Request $request,
        SessionInterface $session
    ): Response {
        $draw = $request->request->all('remove');
        $game = $session->get('game');

        if (count($draw) > 3) {
            $this->addFlash(
                'warning',
                'You can only draw 3 new cards'
            );

            $game->setRound("draw");

            return $this->redirectToRoute('proj_play');
        }

        $numDrawPlayer = $game->drawPlayer($draw);
        $numDrawOpponent = $game->drawOpponent();

        $this->addFlash(
            'notice',
            'You drew ' . $numDrawPlayer . ' new cards'
        );

        $this->addFlash(
            'notice',
            'Opponent drew ' . $numDrawOpponent . ' new cards'
        );

        $game->handleRound();

        $session->set('game', $game);

        return $this->redirectToRoute('proj_play');
    }
}
