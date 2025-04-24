<?php

namespace App\Card;

class Card
{
    /**
     * @var Value $value    String consisting cards value.
     */
    protected $value;

    /**
     * @var Suit $suit  String consisting cards suit.
     */
    protected $suit;

    /**
     * Constructor to initiate a card.
     *
     */
    public function __construct($value, $suit)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    /**
     * Get value of card.
     *
     * @return string String with value.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get suit of card.
     *
     * @return string String with suit.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Get string with suit and value of card.
     *
     * @return string String with suit and value.
     */
    public function getAsString(): string
    {
        return "[{$this->suit}{$this->value}]";
    }
}
