<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Test create deck of cards.
     */
    public function testCreateDeckOfCards()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);

        $res = $deck->getString();
        $this->assertNotEmpty($res);

        $values = $deck->getValues();
        $this->assertNotEmpty($values);

        $suits = $deck->getSuits();
        $this->assertNotEmpty($suits);
    }

    /**
     * Test draw card from deck.
     */
    public function testDrawDeckOfCards()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);

        $deck->draw(2);

        $count = $deck->getCount();
        $this->assertEquals(50, $count);
    }

    /**
     * Test shuffle deck of cards.
     */
    public function testShuffleDeckOfCards()
    {
        $deck = new DeckOfCards();
        $defaultDeck = $deck->createDefaultDeck();

        $deck->shuffle();
        $this->assertNotEquals($deck->getDeck(), $defaultDeck);
    }

    /**
     * Test sort deck of cards.
     */
    public function testSortDeckOfCards()
    {
        $deck = new DeckOfCards();
        $defaultDeck = $deck->createDefaultDeck();

        $deck->shuffle();

        $deck->sort();
        $this->assertEquals($deck->getDeck(), $defaultDeck);
    }
}
