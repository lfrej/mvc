<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Test cases for class Game.
 */
class PlayerTest extends TestCase
{
    /**
     * Test create bank.
     */
    public function testCreatePlayer()
    {
        $player = new Player();
        $this->assertInstanceOf("\App\Project\Player", $player);
    }

    /**
     * Test handle pot.
     */
    public function testPlayerHand()
    {
        $cards = [
            new Card(1, 'H'),
            new Card(10, 'H'),
            new Card(11, 'H'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];

        $player = new Player($cards);

        $hand = $player->getHand();
        $this->assertInstanceOf("\App\Card\CardHand", $hand);

        $player->resetHand();
        $newHand = $player->getHand();
        $this->assertNotEquals($newHand, $hand);
        $this->assertInstanceOf("\App\Card\CardHand", $newHand);
    }

    /**
     * Test handle bet.
     */

    public function testEvaluatePlayerHand()
    {
        $cards = [
            new Card(1, 'H'),
            new Card(1, 'S'),
            new Card(1, 'D'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];

        $player = new Player($cards);

        $hand = $player->evaluateHand();
        $this->assertEquals("Three of a kind", $hand);

        $handWorth = $player->getHandWorth($hand);
        $this->assertEquals("5", $handWorth);
    }

    /**
     * Test handle bet.
     */

    public function testPotPlayer()
    {
        $player = new Player();

        $player->addToPot(12);
        $playerMoney = $player->getPot();
        $this->assertEquals(12, $playerMoney);
    }

    /**
     * Test straight.
     */

    public function testStraightFlush()
    {
        $cards = [
            new Card(4, 'H'),
            new Card(5, 'H'),
            new Card(6, 'H'),
            new Card(7, 'H'),
            new Card(8, 'H'),
        ];

        $player = new Player($cards);

        $hand = $player->evaluateHand();
        $this->assertEquals("Straight flush", $hand);
    }

    public function testStraight()
    {
        $cards = [
            new Card(7, 'H'),
            new Card(8, 'H'),
            new Card(9, 'D'),
            new Card(10, 'C'),
            new Card(11, 'S'),
        ];

        $player = new Player($cards);

        $hand = $player->evaluateHand();
        $this->assertEquals("Straight", $hand);

        $cards = [
            new Card(7, 'H'),
            new Card(6, 'H'),
            new Card(9, 'D'),
            new Card(10, 'C'),
            new Card(11, 'S'),
        ];

        $player = new Player($cards);

        $hand = $player->evaluateHand();
        $this->assertNotEquals("Straight", $hand);
    }

    /**
     * Test straight.
     */

    public function testRoyalFlush()
    {
        $cards = [
            new Card(1, 'H'),
            new Card(10, 'H'),
            new Card(11, 'H'),
            new Card(12, 'H'),
            new Card(13, 'H'),
        ];

        $player = new Player($cards);

        $hand = $player->evaluateHand();
        $this->assertEquals("Royal flush", $hand);
    }

}
