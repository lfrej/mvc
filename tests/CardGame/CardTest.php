<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Test create card.
     */
    public function testCreateCard()
    {
        $card = new Card(10, 'H');
        $this->assertInstanceOf("\App\Card\Card", $card);

        $res = $card->getAsString();
        $this->assertNotEmpty($res);

        $value = $card->getValue();
        $expValue = 10;
        $this->assertEquals($expValue, $value);

        $suit = $card->getSuit();
        $expSuit = 'H';
        $this->assertEquals($expSuit, $suit);
    }

    /**
     * Test create card graphic.
     */
    public function testCreateCardGraphic()
    {
        $card = new CardGraphic(10, 'H');
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);

        $res = $card->getAsString();
        $this->assertNotEmpty($res);
    }
}
