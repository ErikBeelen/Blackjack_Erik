<?php

require_once 'Card.php';

class Deck
{
    private array $cards = [];

    public function __construct()
    {
        $suits = ['Harten', 'Ruiten', 'Klaver', 'Schoppen'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Boer', 'Vrouw', 'Heer'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
        shuffle($this->cards);
    }

    public function drawCard(): Card
    {
        if (empty($this->cards)) {
            throw new Exception("Het deck is leeg!");
        }
        return array_pop($this->cards);
    }
}
?>