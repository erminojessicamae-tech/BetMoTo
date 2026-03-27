<?php
// --- STREAK BREAKER SYSTEM ---
if (!isset($_SESSION['dice_streak'])) $_SESSION['dice_streak'] = ['type' => '', 'count' => 0];

if ($_SESSION['dice_streak']['type'] === 'Big' && $_SESSION['dice_streak']['count'] >= 3) {
    $num1 = rand(0, 2); $num2 = rand(0, 2); // Forces a Small Sum (2-6)
} elseif ($_SESSION['dice_streak']['type'] === 'Small' && $_SESSION['dice_streak']['count'] >= 3) {
    $num1 = rand(3, 5); $num2 = rand(3, 5); // Forces a Big Sum (8-12)
}

$d1 = ($num1 % 6) + 1;
$d2 = ($num2 % 6) + 1;
$dice_sum = $d1 + $d2;
$game_total = "$d1, $d2 (Sum: $dice_sum)";

// Record the new result
$actual_result = ($dice_sum >= 8) ? "Big" : (($dice_sum <= 6) ? "Small" : "Seven");
if ($_SESSION['dice_streak']['type'] === $actual_result) {
    $_SESSION['dice_streak']['count']++;
} else {
    $_SESSION['dice_streak'] = ['type' => $actual_result, 'count' => 1];
}

// --- EVALUATION LOGIC ---
if ($type === "Double") {
    $multiplier = 5.0; $is_win = ($d1 === $d2);
} elseif ($type === "Big") {
    $multiplier = 2.0; $is_win = ($dice_sum >= 8 && $dice_sum <= 12);
} elseif ($type === "Small") {
    $multiplier = 2.0; $is_win = ($dice_sum >= 2 && $dice_sum <= 6);
} else {
    echo json_encode(["success" => false, "message" => "Invalid betting type for Dice Duel."]); exit;
}
?>