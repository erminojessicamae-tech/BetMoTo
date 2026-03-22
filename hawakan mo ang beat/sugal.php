<?php
session_start();    //start the session to access the memory
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["compute"])) {

$type = $_POST["type"] ?? "";      //betting type
$bet = abs($_POST["bet"] ?? 0);       //betting amount
$game = ["The Classic Wheel", "Lucky 7", "Color Quest", "Prime Predictor", "Dice Duel"];      //game type
$money = abs($_POST["money"] ?? 0);     //user's current money
$reward;    //reward amount
//$multiplyer = abs($_POST["multiplyer"] ?? 0);  (tentative)
$num1 = rand(1, 30);     //random number 1
$num2 = rand(1, 30);     //random number 2  
$num3 = rand(1, 30);     //random number 3
$iseven = ($num1 + $num2 + $num3) % 2 === 0;    //check if the sum of the numbers is even
$isodd = ($num1 + $num2 + $num3) % 2 !== 0;     //check if the sum of the numbers is odd

$game_total = 0;
$multiplier = 1.0;   //tentative

if (!isset($_SESSION["money"]) || $bet > $_SESSION["money"]) {
    echo "<h2>Transaction Failed!</h2>";
    echo "You don't have enough money to place that bet. <br>";
    echo "<br><a href='index.html'>Go Back</a>";
    exit;
}

$_SESSION["money"] -= $bet;    //deduct the bet from the user's money

switch ($_POST["game"]) {
    case "The Classic Wheel":
        require "games/classic_wheel.php";    //include the game logic for The Classic Wheel
        break;
    case "Lucky 7":
        require "games/lucky7.php";    //include the game logic for Lucky 7
        break;
    case "Color Quest":
        require "games/color_quest.php";    //include the game logic for Color Quest
        break;
    case "Prime Predictor":
        require "games/prime_predictor.php";    //include the game logic for Prime Predictor
        break;
    case "Dice Duel":
        require "games/dice_duel.php";    //include the game logic for Dice Duel
        break;
    default:
        echo "Invalid game type.";
        exit;

}

    echo "<h2>Game Results!</h2>";
    echo "Game Played: <strong>$game</strong> <br>";
    echo "Raw Numbers Rolled: $num1, $num2, $num3 <br>";
    echo "Calculated Game Total: <strong>$game_total</strong> <br>";
    echo "Potential Multiplier: <strong>$multiplier x</strong> <br>";
    echo "<br><a href='index.html'>Go Back</a>";

}

?>