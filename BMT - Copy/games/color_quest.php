<?php
// Read the array of colors sent from the JS frontend
$selected_colors = $input["selected_colors"] ?? [];
$bet_per_color = count($selected_colors) > 0 ? ($bet / count($selected_colors)) : 0;

// --- PITY SYSTEM (PLAYER LOSE STREAK) ---
if (!isset($_SESSION['color_drought'])) $_SESSION['color_drought'] = 0;

if ($_SESSION['color_drought'] >= 3 && count($selected_colors) > 0) {
    // Pick one of the player's colors and mathematically force Cube 1 to land on it!
    $pity_color = strtoupper($selected_colors[array_rand($selected_colors)]);
    $color_map = ["RED"=>1, "BLUE"=>2, "GOLD"=>3, "GREEN"=>4, "PINK"=>5, "WHITE"=>0];
    $num1 = $color_map[$pity_color]; 
}

function getPeryaColor($number) {
    $mod = $number % 6;
    if ($mod == 1) return "RED"; if ($mod == 2) return "BLUE"; if ($mod == 3) return "GOLD";
    if ($mod == 4) return "GREEN"; if ($mod == 5) return "PINK"; return "WHITE"; 
}

$color1 = getPeryaColor($num1); $color2 = getPeryaColor($num2); $color3 = getPeryaColor($num3);
$rolled = [$color1, $color2, $color3];
$game_total = "$color1, $color2, $color3"; 

$total_won = 0;
foreach ($selected_colors as $color) {
    $matches = count(array_keys($rolled, strtoupper($color)));
    if ($matches > 0) $total_won += ($bet_per_color * 3 * $matches);
}

// Update the drought tracker
if ($total_won > 0) $_SESSION['color_drought'] = 0; // Reset on win
else $_SESSION['color_drought']++; // Increase on lose

$is_win = ($total_won > 0);
$reward = $total_won; 
?>