<?php

class Blackjack
{
    public function scoreHand(array $hand): string
    {
        $score = 0;
        foreach ($hand as $card) {
            $score += $this->getCardValue($card);
        }

        if ($score > 21) {
            return "Busted ($score)";
        }

        if (count($hand) === 2 && $score === 21) {
            return "Blackjack!";
        }

        if (count($hand) >= 5 && $score <= 21) {
            return "Five Card Charlie ($score)";
        }

        if ($score === 21) {
            return "Twenty-One!";
        }

        return (string)$score;
    }

    private function getCardValue(Card $card): int
    {
        $value = $card->getValue();

        if (in_array($value, ['Boer', 'Vrouw', 'Heer'])) {
            return 10;
        } elseif ($value === 'A') {
            return 11;
        } else {
            return (int)$value;
        }
    }
}
?>