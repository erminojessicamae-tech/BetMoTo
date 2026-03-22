<?php
session_start();    //start the session to access the memory
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["compute"])) {

$type = $_POST["type"] ?? "";      //betting type
$bet = abs($_POST["points"] ?? 0);       //betting amount
$game = $_POST["game"] ?? "";      //game type
$reward = 0;    //reward amount

if (!isset($_SESSION["points"]) || $bet > $_SESSION["points"]) {
        echo "<h2>Transaction Failed!</h2>";
        echo "You don't have enough points to place that bet. <br>";
        echo "<br><a href='index.html'>Go Back</a>";
        exit;
    }

$_SESSION["points"] -= $bet;    //deduct the bet from the user's points
//$multiplier = abs($_POST["multiplier"] ?? 0);  (tentative)
$num1 = rand(1, 30);     //random number 1
$num2 = rand(1, 30);     //random number 2  
$num3 = rand(1, 30);     //random number 3
$iseven = ($num1 + $num2 + $num3) % 2 === 0;    //check if the sum of the numbers is even
$isodd = ($num1 + $num2 + $num3) % 2 !== 0;     //check if the sum of the numbers is odd

$game_total = 0;
$multiplier = 1.0;   //tentative

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
$is_win = false;    //assumes the player lost until proven otherwise

switch ($type) {
    case "Even":
        $is_win = ($game_total % 2 === 0);
        break;
    case "Odd":
        $is_win = ($game_total % 2 !== 0);
        break;
    case "Low":
        $is_win = ($game_total >= 1 && $game_total <= 15);
        break;
    case "High":
        $is_win = ($game_total >= 16 && $game_total <= 30);
        break;
    case "Lucky 7":
        $is_win = ($game_total % 7 === 0);
        break;
    case "Yellow":
    case "Blue":
    case "Red":
        $is_win = ($multiplier > 0);
        break;
    case "Prime Number":
        $is_win = isPrime($game_total);
        break;
    case "Big":
        // Check if the sum is between 8 and 12
        $is_win = (isset($dice_sum) && $dice_sum >= 8 && $dice_sum <= 12);
        break;
    case "Small":
        // Check if the sum is between 2 and 6
        $is_win = (isset($dice_sum) && $dice_sum >= 2 && $dice_sum <= 6);
        break;
    case "Triple":
        // Check if all three dice rolled the exact same number
        $is_win = (isset($d1) && $d1 === $d2 && $d2 === $d3);
        break;
    default:
        echo "Invalid betting type.";
        exit;
}

if ($is_win) {
    $reward = $bet * $multiplier;
    $_SESSION["points"] += $reward;    //add the reward to the user's points
    $result_message = "<h2 style='color: green;'>Congratulations, You Win!</h2>";
} else {
    $result_message = "<h2 style='color: red;'>Sorry, You Lose!</h2>";
}

    echo "<h2>Game Results!</h2>";
    echo $result_message;
    echo "Game Played: <strong>$game</strong> <br>";
    echo "Raw Numbers Rolled: $num1, $num2, $num3 <br>";
    echo "Calculated Game Total: <strong>$game_total</strong> <br>";
    echo "Potential Multiplier: <strong>$multiplier x</strong> <br>";
    echo "Your Bet: <strong>$bet points</strong> on <strong>$type</strong> <br>";
    if ($is_win) {
        echo "You Won: <strong>$reward points</strong> <br>";
    } else {
        echo "You Lost: <strong>$bet points</strong> <br>";
    }
    echo "Your New Balance: <strong>{$_SESSION["points"]} points</strong> <br>";
    echo "<br><a href='index.html'>Go Back</a>";

}

?>