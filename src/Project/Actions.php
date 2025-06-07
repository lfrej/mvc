<?php

namespace App\Project;

/**
 * Class that represents a game where you have two players and a deck of cards.
 */
class Actions
{
    private $bank;
    private $lastAction = null;
    private $player;

    /**
     * Constructor to initiate actions for a player.
     *
     * @param GameBank $bank bank for current game.
     * @param Player|null $player player that the actions belong to.
     */
    public function __construct($bank, $player = null)
    {
        $this->bank = $bank;
        $this->player = $player;
    }

    /**
     * Resets actions for player between rounds.
     */
    public function reset()
    {
        $this->lastAction = null;

        $pot = $this->getPlayerContribution();
        $this->setPlayerContribution($pot - $pot);
    }

    /**
     * Get last action.
     *
     * @return string $lastAction action.
     */
    public function getLastAction()
    {
        return $this->lastAction;
    }

    /**
     * Set last action
     */
    public function setLastAction($action)
    {
        $this->lastAction = $action;
    }

    /**
     * Get contribution.
     *
     * @return int How much player previously have put in the pot that round.
     */
    public function getPlayerContribution()
    {
        return $this->player->getContribution();
    }

    /**
     * Set contribution.
     *
     * @param int $contribution Amount to add to player contribution.
     */
    public function setPlayerContribution($contribution)
    {
        $this->player->addContribution($contribution);
    }

    /**
     * Get bank.
     *
     * @return object GameBank.
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Check action.
     */
    public function check()
    {
        $this->setLastAction("checked");

        $this->bank->setCurrentBet(0);

        $this->setPlayerContribution(0);
    }

    /**
     * Bet action.
     *
     * @param int $bet. If bet is null it will be random int.
     * @return int Returns amount added to pot.
     */
    public function bet($bet = null)
    {
        $this->setLastAction("betted");

        if ($bet === null) {
            $bet = random_int(1, 20);
        }

        $this->bank->addToPot($bet);
        $this->bank->setCurrentBet($bet);

        $this->setPlayerContribution($bet);

        return $bet;
    }

    /**
     * Raise action.
     *
     * @param int $addToBet If raise is null it will be random int.
     * @return int Returns amount added to pot.
     */
    public function raise($addToBet = null)
    {
        $this->setLastAction("raised");

        $playerContribution = $this->getPlayerContribution();

        $currentBet = $this->bank->getCurrentBet();

        if ($addToBet === null) {
            $addToBet = random_int(1, 10);
        }

        $newBet = $currentBet + $addToBet;

        $addAmount = $newBet - $playerContribution;

        $this->bank->addToPot($addAmount);
        $this->bank->setCurrentBet($newBet);

        $this->setPlayerContribution($addAmount);

        return $newBet;
    }

    /**
     * Call action.
     *
     * @return int Return amount added to pot.
     */
    public function call()
    {
        $this->setLastAction("called");

        $currentBet = $this->bank->getCurrentBet();

        $playerContribution = $this->getPlayerContribution();

        $toCall = $currentBet - $playerContribution;
        $this->bank->addToPot($toCall);

        $this->setPlayerContribution($toCall);

        return $toCall;
    }

    /**
     * Fold action.
     */
    public function fold()
    {
        $this->setLastAction("folded");
    }
}
