<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameBankTest extends TestCase
{
    /**
     * Test create bank.
     */
    public function testCreateBank()
    {
        $bank = new GameBank();
        $this->assertInstanceOf("\App\Project\GameBank", $bank);
    }

    /**
     * Test handle pot.
     */
    public function testHandlePot()
    {
        $bank = new GameBank();

        $pot = $bank->getTotalPot();
        $this->assertIsInt($pot);

        $bank->addToPot(20);
        $pot = $bank->getTotalPot();
        $this->assertEquals(20, $pot);

        $bank->resetPot();
        $pot = $bank->getTotalPot();
        $this->assertEquals(0, $pot);
    }

    /**
     * Test handle bet.
     */
    public function testHandleBet()
    {
        $bank = new GameBank();

        $bank->setCurrentBet(10);
        $bet = $bank->getCurrentBet();
        $this->assertEquals(10, $bet);
    }

}
