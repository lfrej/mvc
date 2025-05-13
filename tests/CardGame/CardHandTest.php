<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Test create cardhand.
     */
    public function testCreateCardhand()
    {
        $card = new Card(7, 'H');
        $cardhand = new CardHand([$card]);
        $this->assertInstanceOf("\App\Card\Cardhand", $cardhand);

        $res = $cardhand->getString();
        $this->assertNotEmpty($res);
    }

    /**
     * Test count cardhand.
     */
    public function testCountCardhand()
    {
        $card = new Card(7, 'H');
        $cardhand = new CardHand([$card]);

        $count = $cardhand->getCount();
        $this->assertEquals(1, $count);
    }

    /**
     * Test add card to cardhand.
     */
    public function testAddCardhand()
    {
        $card1 = new Card(7, 'H');
        $cardhand = new CardHand([$card1]);

        $card2 = new Card(1, 'S');
        $cardhand->addCards([$card2]);

        $count = $cardhand->getCount();
        $this->assertEquals(2, $count);
    }

    public function testSumCardhand()
    {
        $card1 = new Card(7, 'H');
        $card2 = new Card(1, 'S');
        $cardhand = new CardHand([$card1, $card2]);

        $res = $cardhand->getSum();
        $this->assertEquals(21, $res);
    }
}
