<?php

namespace App\Card;

/**
 * Class that represents a game where you have two players and a deck of cards.
 */
class Game
{
    /**
     * @var CardHand $player   Array consisting of cards in cardhand for player1.
     */
    private $player;

    /**
     * @var CardHand $bank   Array consisting of cards in cardhand for player2.
     */
    private $bank;

    /**
     * @var DeckOfCards $deck   Array consisting cards in deck.
     */
    private $deck;


    /**
     * Constructor to initiate a card hand with one card in hand.
     *
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->player = new CardHand();
        $this->bank = new CardHand();
    }

    /**
     * Get player cardhand.
     *
     * @return object Player cardhand.
     */
    public function getPlayer(): object
    {
        return $this->player;
    }

    /**
     * Get bank cardhand.
     *
     * @return object Bank cardhand.
     */
    public function getBank(): object
    {
        return $this->bank;
    }

    /**
     * Get deck.
     *
     * @return object Deck cardhand.
     */
    public function getDeck(): object
    {
        return $this->deck;
    }

    /**
     * Draw card from deck and add card to card hand.
     *
     */
    public function addCard($drawnCard, $currentPlayer)
    {
        $cardHand = $this->$currentPlayer;

        $cardHand->addCards($drawnCard);

        return $cardHand;
    }

    /**
     * Draw card from deck and add card to cardhand.
     *
     */
    public function drawCard($currentPlayer)
    {
        $drawnCard = $this->deck->draw(1);

        $cardHand = $this->addCard($drawnCard, $currentPlayer);

        return $cardHand;
    }

    /**
     * Get result.
     *
     */
    public function getResult()
    {
        $sumHandPlayer = $this->player->getSum();
        $sumHandBank = $this->bank->getSum();

        if ($sumHandPlayer > 21 && $sumHandBank > 21) {
            return 'Båda förlora!';
        }

        if ($sumHandPlayer > 21 || $sumHandPlayer == $sumHandBank || $sumHandBank == 21) {
            return 'Banken vann!';
        }

        return 'Spelaren vann!';
    }
}
