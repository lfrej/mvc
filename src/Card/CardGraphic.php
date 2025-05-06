<?php

namespace App\Card;

/**
 * Card graphic where UTF-8 represent cards.
 */
class CardGraphic extends Card
{
    /**
     * @var array $representation  Array consisting cards in deck as UTF-8.
     */
    private $representation = [
        'S1' => '🂡', 'H1' => '🂱', 'D1' => '🃁', 'C1' => '🃑',
        'S2' => '🂢', 'H2' => '🂲', 'D2' => '🃂', 'C2' => '🃒',
        'S3' => '🂣', 'H3' => '🂳', 'D3' => '🃃', 'C3' => '🃓',
        'S4' => '🂤', 'H4' => '🂴', 'D4' => '🃄', 'C4' => '🃔',
        'S5' => '🂥', 'H5' => '🂵', 'D5' => '🃅', 'C5' => '🃕',
        'S6' => '🂦', 'H6' => '🂶', 'D6' => '🃆', 'C6' => '🃖',
        'S7' => '🂧', 'H7' => '🂷', 'D7' => '🃇', 'C7' => '🃗',
        'S8' => '🂨', 'H8' => '🂸', 'D8' => '🃈', 'C8' => '🃘',
        'S9' => '🂩', 'H9' => '🂹', 'D9' => '🃉', 'C9' => '🃙',
        'S10' => '🂪', 'H10' => '🂺', 'D10' => '🃊', 'C10' => '🃚',
        'S11' => '🂫', 'H11' => '🂻', 'D11' => '🃋', 'C11' => '🃛',
        'S12' => '🂭', 'H12' => '🂽', 'D12' => '🃍', 'C12' => '🃝',
        'S13' => '🂮', 'H13' => '🂾', 'D13' => '🃎', 'C13' => '🃞',
    ];

    /**
     * Constructor to initiate a card.
     *
     */
    public function __construct($value, $suit)
    {
        parent::__construct($value, $suit);
    }

    /**
     * Create string.
     *
     * @return string Card as string.
     */
    public function getAsString(): string
    {
        return $this->representation[$this->suit . $this->value];
    }
}
