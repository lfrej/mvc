<?php

namespace App\Card;

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
        $game = new Game();
        $this->assertInstanceOf("\App\Card\Game", $game);

        $player = $game->getPlayer();
        $this->assertInstanceOf(CardHand::class, $player);

        $bank = $game->getBank();
        $this->assertInstanceOf(CardHand::class, $bank);

        $deck = $game->getDeck();
        $this->assertInstanceOf(DeckOfCards::class, $deck);
    }

    /**
     * Test draw card in game.
     */
    public function testDrawCardGame()
    {   
        $game = new Game();

        $player = $game->getPlayer();

        $countBefore = $player->getCount();
        $this->assertEquals(0, $countBefore);

        $game->drawCard('player');

        $countAfter = $player->getCount();
        $this->assertEquals(1, $countAfter);
    }

    /**
     * Test player wins game.
     */
    public function testPlayerWinsGame()
    {   
        $game = new Game();
        
        $game->addCard([new Card(7, 'H')], 'player');
        $game->addCard([new Card(1, 'H')], 'player');

        $game->addCard([new Card(6, 'S')], 'bank');
        $game->addCard([new Card(8, 'S')], 'bank');

        $res = $game->getResult();
        $this->assertEquals('Spelaren vann!', $res);
    }

    /**
     * Test bank wins game.
     */
    public function testBankWinsGame()
    {   
        $game = new Game();
        
        $game->addCard([new Card(6, 'H')], 'player');
        $game->addCard([new Card(8, 'H')], 'player');

        $game->addCard([new Card(7, 'S')], 'bank');
        $game->addCard([new Card(1, 'S')], 'bank');

        $res = $game->getResult();
        $this->assertEquals('Banken vann!', $res);
    }

    /**
     * Test both loose game.
     */
    public function testBothLooseGame()
    {   
        $game = new Game();
        
        $game->addCard([new Card(13, 'H')], 'player');
        $game->addCard([new Card(9, 'H')], 'player');

        $game->addCard([new Card(12, 'S')], 'bank');
        $game->addCard([new Card(11, 'S')], 'bank');

        $res = $game->getResult();
        $this->assertEquals('Båda förlora!', $res);
    }

}