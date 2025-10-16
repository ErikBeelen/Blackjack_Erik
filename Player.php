<?php

class Player
{
    private string $name;
    private array $hand = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function showHand(): string
    {
        $cards = [];
        foreach ($this->hand as $card) {
            ob_start();
            $card->show();
            $cards[] = trim(ob_get_clean());
        }
        return implode(' ', $cards);
    }

    public function getHand(): array
    {
        return $this->hand;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
?>
