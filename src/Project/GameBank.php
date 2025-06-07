<?php

namespace App\Project;

/**
 * Class that represents a bank where you a pot and a bet.
 */
class GameBank
{
    private $pot;
    private $bet;

    /**
     * Constructor to initiate a bank for a game.
     *
     */
    public function __construct()
    {
        $this->pot = 0;
        $this->bet = 0;
    }

    /**
     * Add amount to pot.
     *
     * @param int $amount
     */
    public function addToPot($amount)
    {
        $this->pot += $amount;
    }

    /**
     *Get total pot for game.
     */
    public function getTotalPot()
    {
        return $this->pot;
    }

    /**
     * reset pot to 0.
     */
    public function resetPot()
    {
        return $this->pot = 0;
    }

    /**
     * Set current bet to latest bet.
     */
    public function setCurrentBet($lastBet)
    {
        $this->bet = $lastBet;
    }

    /**
     * Get current bet for game.
     *
     * @return int Returns current bet.
     */
    public function getCurrentBet()
    {
        return $this->bet;
    }

}
