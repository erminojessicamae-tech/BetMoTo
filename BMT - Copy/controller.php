<?php
session_start();
header('Content-Type: application/json'); 
error_reporting(E_ALL);
ini_set('display_errors', 0); 

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input["compute"]) || isset($_POST["compute"])) {

    $raw_type = strtoupper($input["type"] ?? $_POST["type"] ?? "");      
    $bet = abs($input["tokens"] ?? $input["points"] ?? $_POST["tokens"] ?? $_POST["points"] ?? 0);     
    $game = $input["game"] ?? $_POST["game"] ?? "";      
    $reward = 0;  

    // We keep the Translator Map because it perfectly sanitizes user inputs
    $type_map = [
        "ODD"       => "Odd",
        "EVEN"      => "Even",
        "LOW"       => "Low",
        "HIGH"      => "High",
        "LUCKY 7"   => "Lucky 7",
        "RED"       => "Red",
        "BLUE"      => "Blue",
        "GOLD"      => "Gold",       
        "GREEN"     => "Green",
        "PINK"      => "Pink",
        "WHITE"     => "White",
        "PRIME"     => "Prime Number", 
        "COMPOSITE" => "Composite",
        "BIG"       => "Big",
        "SMALL"     => "Small",
        "DOUBLE"    => "Double"        
    ];

    $type = $type_map[$raw_type] ?? $raw_type;

    // --- WALLET SECURITY ---
    if (!isset($_SESSION["tokens"])) {
        $_SESSION["tokens"] = 1000;
    }

    if ($bet > $_SESSION["tokens"]) {
        echo json_encode(["success" => false, "message" => "Not enough tokens!"]);
        exit;
    }

    $_SESSION["tokens"] -= $bet;    

    $num1 = rand(1, 30);     
    $num2 = rand(1, 30);       
    $num3 = rand(1, 30);     

    // Initialize defaults before loading games
    $game_total = 0;
    $multiplier = 1.0;   
    $is_win = false;    

    // --- THE GAME ENGINE ---
    // The imported file will automatically process the math and change $is_win
    switch ($game) {
        case "The Classic Wheel":
            require "games/wheel.php";    
            break;
        case "Lucky 7":
            require "games/lucky7.php";    
            break;
        case "Color Quest":
            require "games/color_quest.php";    
            break;
        case "Prime Predictor":
            require "games/prime.php";    
            break;
        case "Dice Duel":
            require "games/dice.php"; 
            break;
        case "Blackjack":
            require "games/blackjack.php"; 
            break;
        default:
            echo json_encode(["success" => false, "message" => "Invalid game type."]);
            exit;
    }

    // --- PAYOUT PROCESSING ---
    if ($is_win) {
        $reward = $bet * $multiplier;
        $_SESSION["tokens"] += $reward;    
    } 

    echo json_encode([
        "success" => true,
        "is_win" => $is_win,
        "reward" => $reward,
        "new_balance" => $_SESSION["tokens"],
        "num1" => $num1,
        "num2" => $num2,
        "num3" => $num3,
        "game_total" => $game_total,
        "multiplier" => $multiplier
    ]);
    exit;
}
?>