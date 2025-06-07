<?php

namespace App\Project;

use App\Card\DeckOfCards;
use App\Card\CardHand;

/**
 * Class that represents a five card draw game where you have two players and a deck of cards.
 */
class FiveCardDrawGame
{
    private $player;
    private $opponent;

    private $deck;
    private $bank;

    private $playerAction;
    private $opponentAction;
    private $decideOpponentAction;

    private $round;
    private $turn;

    /**
     * Constructor to initiate a five card draw game with two players and one bank.
     *
     */
    public function __construct()
    {
        $this->player = new Player();
        $this->opponent = new Player();
        $this->bank = new GameBank();
        $this->decideOpponentAction = new OpponentAction();
        $this->reset();
    }

    /**
     * Reset game.
     */
    public function reset()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();

        $this->deal();

        $this->round = "round1";
        $this->turn = "Player";

        $this->playerAction = new Actions($this->bank, $this->player);
        $this->opponentAction = new Actions($this->bank, $this->opponent);

        $this->bank->resetPot();
        $this->bank->addToPot(20);
    }

    /**
     * Deal 5 cards to each player.
     */
    public function deal()
    {
        $this->getPlayer()->resetHand();
        $this->getOpponent()->resetHand();

        $playerHand = $this->getPlayerHand();
        $opponentHand = $this->getOpponentHand();

        $drawnCards = $this->deck->draw(10);
        $countDrawCards = count($drawnCards);

        for ($i = 0; $i < $countDrawCards; $i++) {
            $card = $drawnCards[$i];

            $i % 2 === 1 ? $playerHand->addCards($card) : $opponentHand->addCards($card);
            /*if ($i % 2 == 1) {
                $playerHand->addCards($card);
            } else {
                $opponentHand->addCards($card);
            }*/
        }
    }

    /**
     * Get player.
     *
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Get opponent.
     *
     * @return Player
     */
    public function getOpponent(): Player
    {
        return $this->opponent;
    }

    /**
     * Get player cardhand.
     *
     * @return CardHand Player cardhand.
     */

    public function getPlayerHand(): CardHand
    {
        return $this->getPlayer()->getHand();
    }

    /**
     * Get opponent cardhand.
     *
     * @return CardHand
     */

    public function getOpponentHand(): CardHand
    {
        return $this->getOpponent()->getHand();
    }

    /**
     * Get deck.
     *
     * @return DeckOfCards
     */
    public function getDeck(): object
    {
        return $this->deck;
    }

    /**
     * Get bank.
     *
     * @return GameBank
     */
    public function getBank(): object
    {
        return $this->bank;
    }

    /**
     * Get playerAction.
     *
     * @return Actions
     */
    public function getPlayerAction(): object
    {
        return $this->playerAction;
    }

    /**
     * Get opponentAction.
     *
     * @return Actions
     */
    public function getOpponentAction(): object
    {
        return $this->opponentAction;
    }

    /**
     * Get last action for player.
     *
     * @return string
     */
    public function getLastPlayerAction(): string
    {
        return $this->playerAction->getLastAction();
    }

    /**
     * Get last action for opponent.
     *
     * @return string
     */
    public function getLastOpponentAction(): string
    {
        return $this->opponentAction->getLastAction();
    }

    /**
     * Get last two actions in game.
     *
     * One action is from player and one opponent.
     *
     * @return array $lastTwoActions Array with last two actions.
     */
    public function getTwoLastActions(): array
    {
        $lastTwoActions = [];

        $lastTwoActions[] = $this->getOpponentAction()->getLastAction();
        $lastTwoActions[] = $this->getPlayerAction()->getLastAction();

        return $lastTwoActions;
    }

    /**
     * Decide opponent (computer) next action.
     *
     * @return int $result how much opponent has added to pot.
     */
    public function decideAction()
    {
        $lastAction = $this->getLastPlayerAction();
        $opponentHand = $this->evaluateOpponentHand();

        $action = $this->decideOpponentAction->decide($lastAction, $opponentHand);

        $result = $this->opponentAction->$action();

        return $result;
    }

    /**
     * Get current round.
     *
     * @return string round.
     */
    public function getRound(): string
    {
        return $this->round;
    }

    /**
     * Get current round.
     */
    public function setRound($round)
    {
        $this->round = $round;
    }

    /**
     * Handle round.
     *
     * If only one player has called or both checked the game will progress to another round.
     */
    public function handleRound()
    {
        $round = $this->getRound();

        $lastTwoActions = $this->getTwoLastActions();

        if ($lastTwoActions === ["checked", "checked"] || in_array("called", $lastTwoActions)) {
            switch ($round) {
                case "round1":
                    $this->setRound("draw");
                    break;
                case "draw":
                    $this->setRound("round2");

                    $this->getPlayerAction()->reset();
                    $this->getOpponentAction()->reset();
                    break;
                case "round2":
                    $this->setRound("showDown");
                    break;
            }
        }

        if (in_array("folded", $lastTwoActions)) {
            $this->setRound("showDown");
        }
    }

    /**
     * Get current turn.
     *
     * @return string turn.
     */
    public function getTurn()
    {
        return $this->turn;
    }

    public function handleTurn()
    {   
        $this->turn = $this->getTurn() === "Player" ? "Opponent" : "Player";
    }

    /**
     * Draw cards for opponent.
     *
     * @return int number of drawn cards.
     */
    public function drawOpponent($drawCards = null): int
    {
        return $this->opponent->draw($this->deck, $drawCards);
    }

    /**
     * Draw cards for player.
     *
     * @return int number of drawn cards.
     */
    public function drawPlayer($drawCards): int
    {
        return $this->player->draw($this->deck, $drawCards);
    }

    /**
     * Evaluate player hand.
     *
     * @return string Return pokerhand like "One pair".
     */
    public function evaluatePlayerHand(): string
    {
        $pokerHand = $this->getPlayer()->evaluateHand();

        return $pokerHand;
    }

    /**
     * Evaluate opponent hand.
     *
     * @return string Return pokerhand like "One pair".
     */
    public function evaluateOpponentHand(): string
    {
        $pokerHand = $this->getOpponent()->evaluateHand();

        return $pokerHand;
    }

    /**
     * Get result of game.
     *
     * @return string Returns string of whose the winner.
     */
    public function getResult(): string
    {
        $opponentEvaluated = $this->evaluateOpponentHand();
        $playerEvaluated = $this->evaluatePlayerHand();

        $playerWorth = $this->getPlayer()->getHandWorth($playerEvaluated);
        $opponentWorth = $this->getOpponent()->getHandWorth($opponentEvaluated);

        $totalPot = $this->getBank()->getTotalPot();

        $nextTurn = strtolower($this->getTurn());
        if (in_array("folded", $this->getTwoLastActions())) {
            $this->$nextTurn->addToPot($totalPot);
            return $this->getTurn();
        }

        if ($playerWorth > $opponentWorth) {
            $this->player->addToPot($totalPot);
            return "Player";
        }

        if ($opponentWorth > $playerWorth) {
            $this->opponent->addToPot($totalPot);
            return "Opponent";
        }

        $this->player->addToPot($totalPot / 2);
        $this->opponent->addToPot($totalPot / 2);

        return "Both";
    }
}
