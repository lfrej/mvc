<?php

namespace App\Project;

/**
 * Class that represents actions for opponent (computer).
 */
class OpponentAction
{
    /**
    * Decide action for opponent.
    *
    * @return string Returns string for action. Example "call".
    */
    public function decide($lastAction, $cardHand)
    {
        $actionsCheck = [
            "Royal flush" => "bet",
            "Four of a kind" => "bet",
            "Full house" => "bet",
            "Flush" => "bet",
            "Straight" => "bet",
            "Three of a kind" => "bet",
            "Two pair" => "bet",
            "One pair" => "check",
            "High rank" => "check"
        ];

        $actionsBet = [
            "Royal flush" => "raise",
            "Four of a kind" => "raise",
            "Full house" => "raise",
            "Flush" => "raise",
            "Straight" => "raise",
            "Three of a kind" => "raise",
            "Two pair" => "call",
            "One pair" => "call",
            "High rank" => "fold"
        ];

        if ($lastAction === "checked") {
            foreach ($actionsCheck as $handName => $action) {
                if ($cardHand === $handName) {
                    return $action;
                }
            }
        }

        if ($lastAction === "betted" || $lastAction === "raised") {
            foreach ($actionsBet as $handName => $action) {
                if ($cardHand === $handName) {
                    return $action;
                }
            }
        }

        if ($lastAction === "called") {
            return "call";
        }

        return "fold";
    }
}
