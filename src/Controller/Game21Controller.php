<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game21Controller extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {

        return $this->render('game/game.html.twig');
    }

    #[Route("/game/play", name: "play")]
    public function play(
        SessionInterface $session
    ): Response {

        $game = new Game();
        if ($session->get('game') !== null) {
            $game = $session->get('game');
        }

        $player = $game->getPlayer();
        $bank = $game->getBank();

        $data = [
            "player" => $player->getString(),
            "bank" => $bank->getString(),
            "sumHandPlayer" => $player->getSum(),
            "sumHandBank" => $bank->getSum()
        ];

        $session->set("game", $game);

        return $this->render('/game/play.html.twig', $data);
    }

    #[Route("/game/play/restart", name: "restart", methods: ['POST'])]
    public function restart(
        SessionInterface $session
    ): Response {
        $session->clear();

        return $this->redirectToRoute('play');
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('/game/doc.html.twig');
    }

    #[Route("/game/play/draw", name: "draw", methods: ['POST'])]
    public function draw(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $deck = $game->getDeck();
        $drawnCard = $deck->draw(1);

        $player = $game->getPlayer();
        $player->addCards($drawnCard);

        $session->set("game", $game);

        return $this->redirectToRoute('play');
    }

    #[Route("/game/play/stop", name: "stop", methods: ['POST'])]
    public function stop(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $deck = $game->getDeck();

        $bank = $game->getBank();
        $player = $game->getPlayer();

        if ($player->getSum() <= 21) {

            while ($bank->getSum() <= 21) {
                $drawnCard = $deck->draw(1);
                $bank->addCards($drawnCard);
            }

            $session->set("game", $game);

            $result = $game->getResult();

            if ($result == "Spelaren vann!") {
                $this->addFlash(
                    'notice',
                    $result
                );
            }

            if ($result == "Banken vann!") {
                $this->addFlash(
                    'warning',
                    $result
                );
            }

            return $this->redirectToRoute('result');
        }

        $this->addFlash(
            'warning',
            'Banken vann! Spelaren fick över 21.'
        );

        return $this->redirectToRoute('result');
    }

    #[Route("/game/result", name: "result")]
    public function result(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");

        $player = $game->getPlayer();
        $bank = $game->getBank();

        $data = [
            "player" => $player->getString(),
            "bank" => $bank->getString(),
            "sumHandPlayer" => $player->getSum(),
            "sumHandBank" => $bank->getSum()
        ];

        return $this->render('/game/result.html.twig', $data);
    }
}
