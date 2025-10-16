<?php

require_once 'Card.php';
require_once 'Deck.php';
require_once 'Player.php';
require_once 'Blackjack.php';
require_once 'Dealer.php';

$deck = new Deck();
$blackjack = new Blackjack();
$dealer = new Dealer($blackjack, $deck);

$dealer->addPlayer(new Player("Speler 1"));
$dealer->addPlayer(new Player("Speler 2"));

$dealer->playGame();
?>