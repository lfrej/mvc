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
    protected $hand = [];

    /**
     * Constructor to initiate a card hand with one card in hand.
     *
     */
    public function __construct(array $drawnCards = [])
    {
        $this->addCards($drawnCards);
    }

    public function getCards(): array
    {
        return $this->hand;
    }

    /**
     * Add cards to card hand.
     *
     */
    public function addCards($drawnCards)
    {
        if (is_array($drawnCards)) {
            foreach ($drawnCards as $card) {
                $this->hand[] = $card;
            }
        } else {
            $this->hand[] = $drawnCards;
        }
    }

    /**
     * Remove cards from card hand.
     *
     */
    public function removeCards($removedCards)
    {
        foreach ($removedCards as $i) {
            if (isset($this->hand[$i])) {
                unset($this->hand[$i]);
            }
        }

        $this->hand = array_values($this->hand);
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
