<?php
$multiplier = 2.5; 

// --- PITY SYSTEM ---
if (!isset($_SESSION['lucky7_drought'])) $_SESSION['lucky7_drought'] = 0;

// If they haven't hit a 7 in 5 rolls, force the first reel to be a 7!
if ($_SESSION['lucky7_drought'] >= 5) {
    $num1 = 7; 
}

// --- EVALUATION LOGIC ---
$is_win = ($num1 % 7 === 0 || $num2 % 7 === 0 || $num3 % 7 === 0);

// Update Drought
if ($is_win) {
    $_SESSION['lucky7_drought'] = 0;
} else {
    $_SESSION['lucky7_drought']++;
}
?>