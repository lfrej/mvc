<?php

namespace App\Project;

use App\Card\Card;
use App\Card\CardHand;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameOutcomesTest extends TestCase
{
    /**
     * Test opponent wins game.
     */
    public function testOpponentWinsGame()
    {
        $game = new FiveCardDrawGame();

        $opponent = $game->getOpponent();
        $opponentCards = [
            new Card(1, 'H'),
            new Card(1, 'H'),
            new Card(2, 'D'),
            new Card(2, 'C'),
            new Card(11, 'S'),
        ];
        $opponent->resetHand($opponentCards);

        $player = $game->getPlayer();
        $playerCards = [
            new Card(7, 'H'),
            new Card(8, 'H'),
            new Card(9, 'D'),
            new Card(10, 'C'),
            new Card(11, 'S'),
        ];
        $player->resetHand($playerCards);


        $gameResult = $game->getResult();
        $this->assertEquals("Opponent", $gameResult);
    }

    /**
     * Test player wins game.
     */
    public function testPlayerWinsGame()
    {
        $game = new FiveCardDrawGame();

        $opponent = $game->getOpponent();
        $opponentCards = [
            new Card(3, 'H'),
            new Card(4, 'H'),
            new Card(1, 'D'),
            new Card(4, 'C'),
            new Card(4, 'S'),
        ];
        $opponent->resetHand($opponentCards);

        $player = $game->getPlayer();
        $playerCards = [
            new Card(1, 'H'),
            new Card(10, 'H'),
            new Card(11, 'H'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];
        $player->resetHand($playerCards);


        $gameResult = $game->getResult();
        $this->assertEquals("Player", $gameResult);
    }

    /**
     * Test the game gets even.
     */
    public function testEvenGame()
    {
        $game = new FiveCardDrawGame();

        $opponent = $game->getOpponent();
        $opponentCards = [
            new Card(1, 'H'),
            new Card(10, 'H'),
            new Card(11, 'H'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];
        $opponent->resetHand($opponentCards);

        $player = $game->getPlayer();
        $playerCards = [
            new Card(1, 'H'),
            new Card(10, 'H'),
            new Card(11, 'H'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];
        $player->resetHand($playerCards);


        $gameResult = $game->getResult();
        $this->assertEquals("Both", $gameResult);
    }

    /**
     * Test player folds..
     */
    public function testPlayerFoldGame()
    {
        $game = new FiveCardDrawGame();

        $opponent = $game->getOpponent();
        $opponentCards = [
            new Card(1, 'H'),
            new Card(10, 'H'),
            new Card(11, 'H'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];
        $opponent->resetHand($opponentCards);

        $player = $game->getPlayer();
        $playerCards = [
            new Card(1, 'H'),
            new Card(10, 'H'),
            new Card(11, 'H'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];
        $player->resetHand($playerCards);

        $playerAction = $game->getPlayerAction();
        $playerAction->fold();

        $game->handleTurn();

        $gameResult = $game->getResult();
        $this->assertEquals("Opponent", $gameResult);
    }
}
