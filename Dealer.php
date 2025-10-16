<?php

class Dealer
{
    private Deck $deck;
    private Blackjack $blackjack;
    private array $players = [];

    public function __construct(Blackjack $blackjack, Deck $deck)
    {
        $this->blackjack = $blackjack;
        $this->deck = $deck;
        $this->players[] = new Player("Dealer");
    }

    public function addPlayer(Player $player): void
    {
        $this->players[] = $player;
    }

    public function playGame(): void
    {
        echo "Nieuw potje Blackjack gestart!" . PHP_EOL;

        foreach ($this->players as $player) {
            $this->giveCard($player);
            $this->giveCard($player);
        }

        $active = array_fill(0, count($this->players), true);

        while (in_array(true, $active, true)) {
            $nonBusted = [];
            foreach ($this->players as $i => $p) {
                $score = $this->getScore($p);
                if (!str_starts_with($score, "Busted")) {
                    $nonBusted[] = $i;
                }
            }

            if (count($nonBusted) === 1) {
                $winner = $this->players[$nonBusted[0]];
                echo $winner->getName() . " wint automatisch omdat alle anderen busted zijn!" . PHP_EOL;
                break;
            }

            if (count($nonBusted) === 0) {
                echo "Alle spelers zijn busted. Geen winnaar." . PHP_EOL;
                break;
            }

        $allBusted = true;
        foreach ($this->players as $p) {
            if (!str_starts_with($this->getScore($p), "Busted")) {
                $allBusted = false;
                break;
            }
        }
        if ($allBusted) {
            echo "Iedereen is busted. Geen winnaar dit potje." . PHP_EOL;
            return;
        }

            foreach ($this->players as $i => $player) {
                if (!$active[$i]) {
                    continue;
                }

                $score = $this->getScore($player);

                if ($this->isGameEndingScore($score)) {
                    echo $player->getName() . " stopt automatisch: $score" . PHP_EOL;
                    $active[$i] = false;
                    continue;
                }

                if ($player->getName() === "Dealer") {
                    $num = $this->getNumericScore($score);
                    if ($num > 0 && $num < 18) {
                        echo "Dealer pakt een kaart..." . PHP_EOL;
                        $this->giveCard($player);
                    } else {
                        echo "Dealer stopt op $score" . PHP_EOL;
                        $active[$i] = false;
                    }
                } else {
                    echo $player->getName() . " heeft: " . $player->showHand() . PHP_EOL;
                    echo "Score: $score" . PHP_EOL;

                    echo "Wil je nog een kaart? (j/n): ";
                    $choice = trim(fgets(STDIN));

                    if (strtolower($choice) === 'j') {
                        $this->giveCard($player);
                    } else {
                        $active[$i] = false;
                    }
                }
            }
        }

        echo PHP_EOL . "=== Eindresultaten ===" . PHP_EOL;
        $dealer = $this->players[0];
        $dealerScore = $this->getScore($dealer);
        echo "Dealer: " . $dealer->showHand() . " => $dealerScore" . PHP_EOL;

        foreach (array_slice($this->players, 1) as $player) {
            $playerScore = $this->getScore($player);
            echo $player->getName() . ": " . $player->showHand() . " => $playerScore" . PHP_EOL;
            echo $this->determineWinner($playerScore, $dealerScore, $player->getName()) . PHP_EOL;
        }
    }

    private function giveCard(Player $player): void
    {
        $card = $this->deck->drawCard();
        $player->addCard($card);
    }

    public function getScore(Player $player): string
    {
        return $this->blackjack->scoreHand($player->getHand());
    }

    private function isGameEndingScore(string $score): bool
    {
        return str_starts_with($score, "Busted") ||
               $score === "Blackjack!" ||
               $score === "Twenty-One!" ||
               str_starts_with($score, "Five Card Charlie");
    }

    private function getNumericScore(string $score): int
    {
        if (preg_match('/\((\d+)\)/', $score, $matches)) {
            return (int)$matches[1];
        }
        if (is_numeric($score)) {
            return (int)$score;
        }
        return 0;
    }

    private function determineWinner(string $playerScore, string $dealerScore, string $playerName): string
    {
        $p = $this->getNumericScore($playerScore);
        $d = $this->getNumericScore($dealerScore);

        if ($playerScore === "Blackjack!" 
            || $playerScore === "Twenty-One!" 
            || str_starts_with($playerScore, "Five Card Charlie")) {
            return "$playerName wint (speciale regel)";
        }

        if ($dealerScore === "Blackjack!" 
            || $dealerScore === "Twenty-One!" 
            || str_starts_with($dealerScore, "Five Card Charlie")) {
            return "Dealer wint van $playerName (speciale regel)";
        }

        if (str_starts_with($playerScore, "Busted")) {
            return "Dealer wint van $playerName (busted)";
        }
        if (str_starts_with($dealerScore, "Busted")) {
            return "$playerName wint (dealer busted)";
        }

        if ($p > $d && $p <= 21 && $d <= 21) {
            return "$playerName wint";
        } elseif ($p === $d) {
            return "Dealer wint van $playerName (gelijkspel)";
        } else {
            return "$playerName heeft verloren";
        }
    }
}
?>