<?php

class Card
{
    private string $suit;
    private string $value;

    public function __construct(string $suit, string $value)
    {
        $this->validateSuit($suit);
        $this->validateValue($value);
        $this->suit = $suit;
        $this->value = $value;
    }

    public function show()
    {
        $symbols = [
            'Harten' => '♥',
            'Ruiten' => '♦',
            'Klaver' => '♣',
            'Schoppen' => '♠',
        ];

        $symbol = $symbols[$this->suit] ?? '?';
        echo "{$this->value} {$symbol}," . PHP_EOL;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function score(): int
    {
        if (in_array($this->value, ['Boer', 'Vrouw', 'Heer'])) {
            return 10;
        } elseif ($this->value === 'A') {
            return 11;
        } else {
            return (int)$this->value;
        }
    }

    private function validateSuit(string $suit)
    {
        $validSuits = ['Harten', 'Ruiten', 'Klaver', 'Schoppen'];
        if (!in_array($suit, $validSuits, true)) {
            throw new InvalidArgumentException("Ongeldige suit: $suit");
        }
    }

    private function validateValue(string $value)
    {
        $validValues = [
            'A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Boer', 'Vrouw', 'Heer'
        ];
        if (!in_array($value, $validValues, true)) {
            throw new InvalidArgumentException("Ongeldige value: $value");
        }
    }
}
?>
