<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class ActionsTest extends TestCase
{
    /**
     * Test create bank.
     */
    public function testCreateActions()
    {
        $player = new Player();
        $bank = new GameBank();
        $actions = new Actions($bank, $player);
        $this->assertInstanceOf("\App\Project\Actions", $actions);

        $lastAction = $actions->getLastAction();
        $this->assertEquals("none", $lastAction);

        $actions->setLastAction("called");
        $lastAction = $actions->getLastAction();
        $this->assertEquals("called", $lastAction);
    }

    /**
     * Test create bank.
     */
    public function testCheck()
    {
        $player = new Player();
        $bank = new GameBank();
        $actions = new Actions($bank, $player);
        $actions->check();

        $lastAction = $actions->getLastAction();
        $this->assertEquals("checked", $lastAction);
    }

    /**
     * Test create bank.
     */
    public function testBet()
    {
        $player = new Player();
        $bank = new GameBank();
        $actions = new Actions($bank, $player);
        $actions->bet(10);

        $lastAction = $actions->getLastAction();
        $this->assertEquals("betted", $lastAction);

        $bank = $actions->getBank();
        $pot = $bank->getTotalPot();
        $bet = $bank->getCurrentBet();

        $this->assertEquals("10", $bet);
        $this->assertEquals("10", $pot);
    }


    /**
     * Test create bank.
     */
    public function testRaise()
    {
        $player1 = new Player();
        $player2 = new Player();

        $bank = new GameBank();
        $actionsPlayer1 = new Actions($bank, $player1);
        $actionsPlayer2 = new Actions($bank, $player2);

        $actionsPlayer1->bet(10);
        $actionsPlayer2->raise(5);

        $actionsPlayer1->raise(2);
        $actionsPlayer2->raise(1);

        $contributionPlayer2 = $actionsPlayer2->getPlayerContribution();
        $this->assertEquals("18", $contributionPlayer2);

        $lastAction = $actionsPlayer2->getLastAction();
        $this->assertEquals("raised", $lastAction);

        $bank = $actionsPlayer2->getBank();
        $pot = $bank->getTotalPot();
        $bet = $bank->getCurrentBet();

        $this->assertEquals("18", $bet);
        $this->assertEquals("35", $pot);

        $actionsPlayer1->bet();
        $actionsPlayer1->raise();

        $bank = $actionsPlayer1->getBank();
        $pot = $bank->getTotalPot();
        $bet = $bank->getCurrentBet();

        $this->assertIsInt($bet);
        $this->assertIsInt($pot);
    }

    /**
     * Test create bank.
     */
    public function testCall()
    {
        $player1 = new Player();
        $player2 = new Player();
        $bank = new GameBank();
        $player1Actions = new Actions($bank, $player1);
        $player2Actions = new Actions($bank, $player2);
        $player1Actions->bet(10);
        $player2Actions->call();

        $pot = $bank->getTotalPot();
        $bet = $bank->getCurrentBet();

        $this->assertEquals("10", $bet);
        $this->assertEquals("20", $pot);
    }

}
