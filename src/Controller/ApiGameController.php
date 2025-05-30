<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiGameController extends AbstractController
{
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

        return new JsonResponse($data);
    }

}
