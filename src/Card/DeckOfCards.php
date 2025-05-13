<?php

namespace App\Card;

/**
 * A deck of cards, consisting of cards.
 */
class DeckOfCards
{
    /**
     * @var array $deck   Array consisting cards in deck.
     */
    private $deck = [];

    /**
     * Constructor to initiate the deck of cards.
     *
     */
    public function __construct()
    {
        $suits = array('H', 'D', 'C', 'S');
        $values = array('1','2','3','4','5','6','7','8','9','10','11','12','13');

        $this->deck  = [];
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $card = new CardGraphic($value, $suit);
                $this->deck[] = $card;
            }
        }
    }

    /**
     * Create default deck which is sorted by suit and value.
     *
     * @return array Array of all cards in deck sorted by suit and value.
     */
    public function createDefaultDeck()
    {
        $suits = array('H', 'D', 'C', 'S');
        $values = array('1','2','3','4','5','6','7','8','9','10','11','12','13');

        $defaultDeck = [];
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $card = new CardGraphic($value, $suit);
                $defaultDeck[] = $card;
            }
        }

        return $defaultDeck;
    }

    /**
     * Sort deck.
     *
     * @return array Array of all cards in deck sorted by suit and value.
     */
    public function sort()
    {
        $sortedDeck = [];

        $defaultDeck = $this->createDefaultDeck();
        $countDefaultDeck = count($defaultDeck);

        for ($i = 0; $i < $countDefaultDeck; $i++) {
            $defaultCard = $defaultDeck[$i];
            foreach ($this->deck as $currentCard) {
                if ($defaultCard->getSuit() === $currentCard->getSuit() && $defaultCard->getValue() === $currentCard->getValue()) {
                    $sortedDeck[] = $defaultCard;
                    break;
                }
            }
        }

        $this->deck = $sortedDeck;

        return $this->deck;
    }

    /**
     * Draw card from deck.
     *
     * @return array of all drawn cards.
     */
    public function draw($number): array
    {
        $drawnCards = [];
        for ($i = 0; $i < $number; $i++) {
            $drawnCards[] = $this->deck[$i];
        }

        $drawnCards = array_splice($this->deck, 0, $number);

        return $drawnCards;
    }

    /**
     * Get string for deck.
     *
     * @return array Array of cards.
     */
    public function getString(): array
    {
        $cards = [];
        foreach ($this->deck as $card) {
            $cards[] = $card->getAsString();
        }
        return $cards;
    }

    /**
     * Shuffle deck.
     *
     */
    public function shuffle()
    {
        shuffle($this->deck);
    }

    /**
     * Get values.
     *
     * @return array Array of values.
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    /**
     * Get suits.
     *
     * @return array Array of suits.
     */
    public function getSuits(): array
    {
        $suits = [];
        foreach ($this->deck as $card) {
            $suits[] = $card->getSuit();
        }
        return $suits;
    }


    /**
     * Get deck.
     *
     * @return array Array of cards in deck.
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Count deck.
     *
     * @return int Int of how many cards is in deck.
     */
    public function getCount()
    {
        return count($this->deck);
    }
}
