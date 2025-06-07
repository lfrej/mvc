<?php

namespace App\Project;

use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Class that represents a player.
 */
class Player
{
    private $hand;
    private $pot = 0;
    private $contribution = 0;

    /**
     * Constructor to initiate a player with a cardhand.
     *
     * @param array $cards Array with cards.
     */
    public function __construct($cards = [])
    {
        $this->hand = new CardHand($cards);
    }

    /**
     * Get player hand.
     *
     * @return object Players hand.
     */
    public function getHand(): object
    {
        return $this->hand;
    }

    /**
     * Reset hand.
     */
    public function resetHand($cards = [])
    {
        $this->hand = new CardHand($cards);
    }

    /**
     * Get player total pot.
     *
     * @return int Player total pot.
     */
    public function getPot(): int
    {
        return $this->pot;
    }

    /**
     * Add prizemoney to pot
     *
     * @param int $prizeMoney How much player has won.
     */
    public function addToPot($prizeMoney)
    {
        $this->pot += $prizeMoney;
    }

    /**
     * Get player contribution.
     *
     * @return int Amount player has contributed to game pot.
     */
    public function getContribution(): int
    {
        return $this->contribution;
    }

    /**
     * Add to player contribution.
     *
     *  @param int $contribution How much player has added to pot.
     */
    public function addContribution($contribution)
    {
        $this->contribution += $contribution;
    }

    /**
     * Evaluate player hand.
     *
     * @return string Returns what hand player has. Ex. "Three of a kind".
     */
    public function evaluateHand()
    {
        $evaluation = new EvaluateCardHand($this->hand->getCards());
        $result = $evaluation->checkPokerHand();

        return $result;
    }

    /**
     * Evaluate worth of hand.
     *
     * @param string $evaluatedHand What the hand is. Ex. "Three of a kind".
     * @return int Returns what the hand is worth.
     */
    public function getHandWorth($evaluatedHand)
    {
        $handValues = [
            "Royal flush" => 10,
            "Straight flush" => 9,
            "Four of a kind" => 8,
            "Full house" => 7,
            "Flush" => 6,
            "Three of a kind" => 5,
            "Two pair" => 4,
            "One pair" => 3,
            "High rank" => 2
        ];

        return $handValues[$evaluatedHand] ?? 0;
    }

    /**
     * Draw/change players hand.
     *
     * @param DeckOfCards $deck The deck that is used in game.
     * @param array|null $drawCards Optional. Which cards to change.
     * @return int Returns how many cards player has change.
     */
    public function draw($deck, $drawCards = null)
    {
        if ($drawCards === null) {
            $numberOfCards = random_int(1, 3);
            $indexes = range(0, 4);

            $drawCards = array_rand($indexes, $numberOfCards);

            if (!is_array($drawCards)) {
                $drawCards = [$drawCards];
            }
        }

        $this->hand->removeCards($drawCards);
        $drawnCards = $deck->draw(count($drawCards));
        $this->hand->addCards($drawnCards);

        return count($drawCards);
    }
}
