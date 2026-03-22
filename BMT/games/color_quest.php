<?php
// games/color_quest.php

// 1. Helper function to figure out the color based on your modulo math
function getPeryaColor($number) {
    if ($number % 3 == 1) return "Red";
    if ($number % 3 == 2) return "Blue";
    return "Yellow"; // If the remainder is 0
}

// 2. Convert the 3 random numbers into their corresponding colors
$color1 = getPeryaColor($num1);
$color2 = getPeryaColor($num2);
$color3 = getPeryaColor($num3);

// 3. Display the 3 colors rolled as the "Game Total"
$game_total = "$color1, $color2, $color3";

// 4. Count how many times the player's chosen color ($type) rolled
$match_count = 0;
if ($type === $color1) $match_count++;
if ($type === $color2) $match_count++;
if ($type === $color3) $match_count++;

// 5. Calculate the Multiplier
if ($match_count == 1) {
    $multiplier = 2.0; // 1 match
} elseif ($match_count == 2) {
    $multiplier = 3.0; // 2 matches
} elseif ($match_count == 3) {
    $multiplier = 4.0; // 3 matches (Jackpot!)
} else {
    $multiplier = 0.0; // No matches
}
?>