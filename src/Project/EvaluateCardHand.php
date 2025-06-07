<?php

namespace App\Project;

/**
 * Class that represents a game where you have two players and a deck of cards.
 */
class EvaluateCardHand
{
    private $hand;

    public function __construct($hand)
    {
        $this->hand = $hand;
    }

    /**
     * Count how many of each card, by suit or rank.
     *
     * @param string $chosen
     * @return array Counts
     */
    public function getAll($chosen): array
    {
        $counts = [];

        foreach ($this->hand as $card) {
            $key = $card->getValue();

            if ($chosen === "suits") {
                $key = $card->getSuit();
            }

            if (!isset($counts[$key])) {
                $counts[$key] = 0;
            }
            $counts[$key]++;
        }

        return $counts;
    }

    /**
     * Counts how many of each suit or rank.
     *
     * @param string $chosen
     * @return array Counts
     */
    public function getNum($chosen): array
    {
        $counts = $this->getAll($chosen);

        $numCounts = [];
        foreach ($counts as $num) {
            $numCounts[] = $num;
        }

        rsort($numCounts);

        return $numCounts;
    }

    /**
     * Checks if straight.
     *
     * @return bool
     */
    public function checkStraight(): bool
    {
        $ranks = $this->getNum("ranks");

        if ($ranks != [1, 1, 1, 1, 1]) {
            return false;
        }

        $values = [];
        foreach ($this->hand as $card) {
            $values[] = (int)$card->getValue();
        }

        sort($values);

        $values == [1, 10, 11, 12, 13] ? [10, 11, 12, 13, 14] : $values;

        $countValues = count($values);
        for ($i = 0; $i < $countValues - 1; $i++) {
            if ($values[$i] + 1 !== $values[$i + 1]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if royal flush.
     *
     * @return bool
     */
    public function checkRoyalFlush(): bool
    {
        $suits = $this->getNum("suits");

        if ($suits != [5]) {
            return false;
        }

        $values = [];
        foreach ($this->hand as $card) {
            $values[] = (int)$card->getValue();
        }

        sort($values);

        $royalFlush = [1, 10, 11, 12, 13];

        if ($values === $royalFlush) {
            return true;
        }

        return false;
    }

    /**
     * Checks poker hand and returns with name of current hand.
     *
     * @return string
     */
    public function checkPokerHand(): string
    {
        $suits = $this->getNum("suits");
        $ranks = $this->getNum("ranks");
        $isStraight = $this->checkStraight();
        $isRoyalFlush = $this->checkRoyalFlush();

        if ($isRoyalFlush === true) {
            return "Royal flush";
        }

        if ($isStraight === true && $suits === [5]) {
            return "Straight flush";
        }

        if ($isStraight === true) {
            return "Straight";
        }

        $hands = [
            "Four of a kind" => [4, 1],
            "Full house" => [3, 2],
            "Flush" => [5],
            "Three of a kind" => [3, 1, 1],
            "Two pair" => [2, 2, 1],
            "One pair" => [2, 1, 1, 1],
            "High rank" => [1, 1, 1, 1, 1]
        ];

        $pokerHand = $hands[$ranks]; 

        return $pokerHand;
    }
}
