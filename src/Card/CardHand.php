<?php

namespace App\Card;

/**
 * Class for cardhand that consists of cards.
 */
class CardHand
{
    /**
     * @var array $hand   Array consisting cards.
     */
    private $hand = [];

    /**
     * Constructor to initiate a card hand with one card in hand.
     *
     */
    public function __construct(array $drawnCards = [])
    {
        $this->addCards($drawnCards);
    }

    /**
     * Add cards to card hand.
     *
     */
    public function addCards($drawnCards)
    {
        foreach ($drawnCards as $card) {
            $this->hand[] = $card;
        }
    }

    /**
     * Get sum of hand.
     *
     * @return int Int with value.
     */
    public function getSum()
    {
        $total = 0;
        if (!empty($this->hand)) {
            foreach ($this->hand as $card) {
                $total += $card->getValue();

                if ($card->getValue() == 1) {
                    if ($total + 13 <= 21) {
                        $total += 13;
                    }
                }
            }
        }

        return $total;
    }

    /**
     * Get string for hand.
     *
     * @return array Array of cards.
     */
    public function getString(): array
    {
        $cards = [];
        foreach ($this->hand as $card) {
            $cards[] = $card->getAsString();
        }
        return $cards;
    }

    /**
     * Count len cardhand.
     *
     * @return int Int of how many cards in hand.
     */
    public function getCount()
    {
        return count($this->hand);
    }

}
