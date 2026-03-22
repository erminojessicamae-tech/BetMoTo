<?php
// games/prime_predictor.php

// 1. We only need one number for this game (1-30)
$game_total = $num1;
$multiplier = 3.0; // 3x payout since primes are slightly harder to hit

// 2. Define the Prime checking function
// We wrap it in function_exists so PHP doesn't panic if it accidentally loads twice
if (!function_exists('isPrime')) {
    function isPrime($number) {
        // Numbers less than 2 (like 1) are mathematically not prime
        if ($number < 2) {
            return false;
        }
        
        // Loop to check for divisors. 
        // We only need to check up to the square root of the number for efficiency!
        for ($i = 2; $i <= sqrt($number); $i++) {
            if ($number % $i == 0) {
                // If it divides evenly by anything other than 1 and itself, it's not prime
                return false; 
            }
        }
        
        // If it survives the loop without triggering false, it must be prime!
        return true;
    }
}
?>