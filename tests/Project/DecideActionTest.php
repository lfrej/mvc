<?php

namespace App\Project;

use App\Card\Card;
use App\Card\CardHand;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class DecideActionTest extends TestCase
{
    /**
     * Test player checks.
     */
    public function testPlayerCheckedGame()
    {
        $game = new FiveCardDrawGame();

        $opponent = $game->getOpponent();
        $opponentCards = [
            new Card(3, 'H'),
            new Card(2, 'H'),
            new Card(1, 'D'),
            new Card(4, 'C'),
            new Card(4, 'S'),
        ];
        $opponent->resetHand($opponentCards);

        $playerAction = $game->getPlayerAction();

        $playerAction->check();

        $action = $game->decideAction();
        $this->assertEquals(0, $action);
    }

    /**
     * Test player bets.
     */
    public function testPlayerBettedGame()
    {
        $game = new FiveCardDrawGame();

        $opponent = $game->getOpponent();
        $opponentCards = [
            new Card(3, 'H'),
            new Card(2, 'H'),
            new Card(1, 'D'),
            new Card(4, 'C'),
            new Card(4, 'S'),
        ];
        $opponent->resetHand($opponentCards);

        $playerAction = $game->getPlayerAction();

        $playerAction->bet(1);

        $action = $game->decideAction();
        $this->assertEquals(1, $action);
    }

    /**
     * Test player bets.
     */
    public function testPlayerCalledGame()
    {
        $game = new FiveCardDrawGame();

        $opponent = $game->getOpponent();
        $opponentCards = [
            new Card(3, 'H'),
            new Card(2, 'H'),
            new Card(1, 'D'),
            new Card(4, 'C'),
            new Card(4, 'S'),
        ];
        $opponent->resetHand($opponentCards);

        $playerAction = $game->getPlayerAction();

        $playerAction->call();

        $action = $game->decideAction();
        $this->assertEquals(0, $action);
    }
}
