<?php
// games/dice_duel.php

// 1. Convert the 1-30 random numbers into standard 1-6 dice rolls
$d1 = ($num1 % 6) + 1;
$d2 = ($num2 % 6) + 1;
$d3 = ($num3 % 6) + 1;

// 2. Calculate the sum of the dice
$dice_sum = $d1 + $d2 + $d3;

// 3. Set the Game Total text so the player sees exactly what they rolled
$game_total = "$d1, $d2, $d3 (Sum: $dice_sum)";

// 4. Set the multipliers
if ($type === "Triple") {
    $multiplier = 5.0; // Huge 5x payout for a Triple!
} else {
    $multiplier = 2.0; // Standard 2x payout for Big/Small
}
?>