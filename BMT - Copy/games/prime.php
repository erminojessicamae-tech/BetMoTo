<?php
if (!function_exists('isPrime')) {
    function isPrime($number) {
        if ($number < 2) return false;
        for ($i = 2; $i <= sqrt($number); $i++) { if ($number % $i == 0) return false; }
        return true;
    }
}

// --- STREAK BREAKER SYSTEM ---
if (!isset($_SESSION['prime_streak'])) $_SESSION['prime_streak'] = ['type' => '', 'count' => 0];

if ($_SESSION['prime_streak']['type'] === 'Composite' && $_SESSION['prime_streak']['count'] >= 3) {
    $primes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29];
    $num1 = $primes[array_rand($primes)]; // Force Prime
} elseif ($_SESSION['prime_streak']['type'] === 'Prime Number' && $_SESSION['prime_streak']['count'] >= 3) {
    $composites = [4, 6, 8, 9, 10, 12, 14, 15, 16, 18, 20, 21, 22, 24, 25, 26, 27, 28, 30];
    $num1 = $composites[array_rand($composites)]; // Force Composite
}

$game_total = $num1;
$actual_result = ($game_total == 1 || isPrime($game_total)) ? 'Prime Number' : 'Composite';

if ($_SESSION['prime_streak']['type'] === $actual_result) $_SESSION['prime_streak']['count']++;
else $_SESSION['prime_streak'] = ['type' => $actual_result, 'count' => 1];

// --- EVALUATION LOGIC ---
$multiplier = ($type === "Prime Number") ? 3.0 : 1.5; 

if ($type === "Prime Number") {
    $is_win = ($game_total == 1 || isPrime($game_total));
} elseif ($type === "Composite") {
    $is_win = ($game_total == 1 || (!isPrime($game_total) && $game_total > 1));
} else {
    echo json_encode(["success" => false, "message" => "Invalid betting type for Prime."]); exit;
}
?>