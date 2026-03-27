<?php
$multiplier = 2.0; 

// --- STREAK BREAKER SYSTEM ---
if (!isset($_SESSION['wheel_streak'])) $_SESSION['wheel_streak'] = ['type' => '', 'count' => 0];

// Intercept and rewrite the wheel spin if the streak is too high
if ($_SESSION['wheel_streak']['type'] === 'Odd' && $_SESSION['wheel_streak']['count'] >= 3) {
    $num1 = rand(1, 15) * 2; // Force an Even number
} elseif ($_SESSION['wheel_streak']['type'] === 'Even' && $_SESSION['wheel_streak']['count'] >= 3) {
    $num1 = rand(0, 14) * 2 + 1; // Force an Odd number
}

// Record the new result into the server's memory
$actual_result = ($num1 % 2 !== 0) ? "Odd" : "Even";
if ($_SESSION['wheel_streak']['type'] === $actual_result) {
    $_SESSION['wheel_streak']['count']++;
} else {
    $_SESSION['wheel_streak'] = ['type' => $actual_result, 'count' => 1];
}

// --- EVALUATION LOGIC ---
$game_total = $num1;

if ($type === "Odd") {
    $is_win = ($game_total % 2 !== 0);
} elseif ($type === "Even") {
    $is_win = ($game_total % 2 === 0);
} elseif ($type === "Low") {
    $is_win = ($game_total >= 1 && $game_total <= 15);
} elseif ($type === "High") {
    $is_win = ($game_total >= 16 && $game_total <= 30);
} else {
    echo json_encode(["success" => false, "message" => "Invalid betting type for Wheel."]); exit;
}
?>