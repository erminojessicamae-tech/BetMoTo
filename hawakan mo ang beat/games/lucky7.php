<?php
// games/lucky7.php

// The game mechanic: Sum the numbers, give a huge bonus if a 7 was rolled.
$game_total = $num1 + $num2 + $num3;

if (in_array(7, [$num1, $num2, $num3])) {
    $multiplier = 7.0;
} else {
    $multiplier = 1.2;
}

// That's it! No need to exit or close anything. It hands control back to sugal.php.
?>