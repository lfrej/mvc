<?php

namespace App\Project;

use App\Card\Card;
use App\Card\CardHand;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    /**
     * Test create game.
     */
    public function testCreateGame()
    {
        $game = new FiveCardDrawGame();
        $this->assertInstanceOf("\App\Project\FiveCardDrawGame", $game);

        $deck = $game->getDeck();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);

        $bank = $game->getBank();
        $this->assertInstanceOf("\App\Project\GameBank", $bank);
    }

    /**
     * Test actions.
     */
    public function testActionsGame()
    {
        $game = new FiveCardDrawGame();
        $playerAction = $game->getPlayerAction();
        $this->assertInstanceOf("\App\Project\Actions", $playerAction);

        $playerAction->bet(2);

        $lastPlayerAction = $game->getLastPlayerAction();

        $this->assertEquals("betted", $lastPlayerAction);

        $opponentAction = $game->getOpponentAction();
        $this->assertInstanceOf("\App\Project\Actions", $opponentAction);

        $opponentAction->call();

        $lastOpponentAction = $game->getLastOpponentAction();

        $this->assertEquals("called", $lastOpponentAction);

        $lastTwoActions = $game->getTwoLastActions();
        $this->assertEquals(["called", "betted"], $lastTwoActions);
    }

    /**
     * Test actions.
     */
    public function testRoundGame()
    {
        $game = new FiveCardDrawGame();
        $round = $game->getRound();

        $this->assertEquals("round1", $round);

        $playerAction = $game->getPlayerAction();
        $playerAction->check();

        $opponentAction = $game->getOpponentAction();
        $opponentAction->check();

        $game->handleRound();
        $round = $game->getRound();
        $this->assertEquals("draw", $round);

        $game->handleRound();
        $round = $game->getRound();
        $this->assertEquals("round2", $round);

        $playerAction->bet(1);

        $opponentAction->call();

        $game->handleRound();
        $round = $game->getRound();
        $this->assertEquals("showDown", $round);
    }

    /**
     * Test fold actions.
     */
    public function testFoldGame()
    {
        $game = new FiveCardDrawGame();

        $playerAction = $game->getPlayerAction();
        $playerAction->fold();

        $game->handleRound();
        $round = $game->getRound();
        $this->assertEquals("showDown", $round);
    }

    /**
     * Test turn.
     */
    public function testTurnGame()
    {
        $game = new FiveCardDrawGame();
        $turn = $game->getTurn();
        $this->assertEquals("Player", $turn);

        $game->handleTurn();
        $turn = $game->getTurn();
        $this->assertEquals("Opponent", $turn);

        $game->handleTurn();
        $turn = $game->getTurn();
        $this->assertEquals("Player", $turn);
    }

    /**
     * Test draw.
     */
    public function testDrawGame()
    {
        $game = new FiveCardDrawGame();

        $playerDraw = $game->drawPlayer([0, 1]);
        $this->assertEquals(2, $playerDraw);

        $opponentDraw = $game->drawOpponent();
        $this->assertContains($opponentDraw, [1, 2, 3]);
    }

    /**
     * Test evaluate playerhand.
     */
    public function testEvaluatePlayerHand()
    {
        $game = new FiveCardDrawGame();
        $player = $game->getPlayer();
        $cards = [
            new Card(7, 'H'),
            new Card(8, 'H'),
            new Card(9, 'D'),
            new Card(10, 'C'),
            new Card(11, 'S'),
        ];
        $player->resetHand($cards);

        $pokerHand = $game->evaluatePlayerHand();

        $this->assertEquals("Straight", $pokerHand);
    }

    /**
     * Test evaluate opponent hand.
     */
    public function testEvaluateOpponentHand()
    {
        $game = new FiveCardDrawGame();
        $opponent = $game->getOpponent();
        $cards = [
            new Card(1, 'H'),
            new Card(1, 'H'),
            new Card(2, 'D'),
            new Card(2, 'C'),
            new Card(11, 'S'),
        ];
        $opponent->resetHand($cards);

        $pokerHand = $game->evaluateOpponentHand();

        $this->assertEquals("Two pair", $pokerHand);
    }
}
