<?php
// games/blackjack.php

$action = $input["action"] ?? "";

// --- HELPER FUNCTIONS ---
function createDeck() {
    $suits = ['♠', '♥', '♣', '♦'];
    $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
    $deck = [];
    foreach ($suits as $suit) { foreach ($values as $value) { $deck[] = ['suit' => $suit, 'value' => $value]; } }
    shuffle($deck); return $deck;
}

function calculateScore($hand) {
    $score = 0; $aces = 0;
    foreach ($hand as $card) {
        if ($card['value'] === 'A') { $score += 11; $aces++; } 
        elseif (in_array($card['value'], ['J', 'Q', 'K'])) { $score += 10; } 
        else { $score += (int)$card['value']; }
    }
    while ($score > 21 && $aces > 0) { $score -= 10; $aces--; }
    return $score;
}

// ACTION 1: INITIAL DEAL
if ($action === "bet") {
    if (!isset($_SESSION['bj_lose_streak'])) $_SESSION['bj_lose_streak'] = 0;

    $deck = createDeck();
    $player_hand = [];
    
    // --- THE PITY SYSTEM ---
    // If the player lost 3 times in a row, dig through the deck and give them an Ace!
    if ($_SESSION['bj_lose_streak'] >= 3) {
        foreach ($deck as $k => $card) {
            if ($card['value'] === 'A') {
                $player_hand[] = $card;
                unset($deck[$k]);
                break;
            }
        }
        $deck = array_values($deck); // Re-index the deck
    } else {
        $player_hand[] = array_pop($deck);
    }

    $player_hand[] = array_pop($deck); // Second card
    $dealer_hand = [array_pop($deck), array_pop($deck)];

    $status = "playing"; $winnings = 0; $message = "";

    // Check for Instant Blackjack
    $pScore = calculateScore($player_hand);
    if ($pScore == 21) {
        $status = "win"; $message = "BLACKJACK!"; $winnings = $bet * 3; 
        $_SESSION["tokens"] += $winnings;
        $_SESSION['bj_lose_streak'] = 0; // Reset streak on win
    }

    $_SESSION["blackjack"] = [
        "deck" => $deck, "player_hand" => $player_hand, "dealer_hand" => $dealer_hand,
        "bet" => $bet, "status" => $status
    ];

    echo json_encode([
        "success" => true, "status" => $status, "message" => $message,
        "player_hand" => $player_hand, "player_score" => $pScore,
        "dealer_hand" => $status === "playing" ? [$dealer_hand[0]] : $dealer_hand,
        "dealer_score" => $status === "playing" ? calculateScore([$dealer_hand[0]]) : calculateScore($dealer_hand),
        "new_balance" => $_SESSION["tokens"], "winnings" => $winnings
    ]);
    exit;
}

// ACTION 3: PLAYER STANDS (DEALER AI TAKES OVER)
if ($action === "stand") {
    if (!isset($_SESSION["blackjack"]) || $_SESSION["blackjack"]["status"] !== "playing") {
        if ($state["status"] === "win" || $state["status"] === "tie") $_SESSION['bj_lose_streak'] = 0; // Reset on win/tie
        elseif ($state["status"] === "lose") $_SESSION['bj_lose_streak']++; // Increase on loss
         echo json_encode(["success" => false, "message" => "No active game."]); exit;
    }

    $state = &$_SESSION["blackjack"];
    $pScore = calculateScore($state["player_hand"]);
    $dScore = calculateScore($state["dealer_hand"]);

    // Dealer Rule: Hit until 17
    while ($dScore < 17) {
        $state["dealer_hand"][] = array_pop($state["deck"]);
        $dScore = calculateScore($state["dealer_hand"]);
    }

    $winnings = 0; $message = "";

    // Evaluate Win/Loss
    if ($dScore > 21) {
        $state["status"] = "win"; $message = "DEALER BUSTS!"; $winnings = $state["bet"] * 3;
    } elseif ($pScore > $dScore) {
        $state["status"] = "win"; $message = "YOU WIN!"; $winnings = $state["bet"] * 3;
    } elseif ($pScore < $dScore) {
        $state["status"] = "lose"; $message = "DEALER WINS";
    } else {
        $state["status"] = "tie"; $message = "PUSH (TIE)"; $winnings = $state["bet"];
    }

    $_SESSION["tokens"] += $winnings; // Add winnings securely

    echo json_encode([
        "success" => true, "status" => $state["status"], "message" => $message,
        "player_hand" => $state["player_hand"], "player_score" => $pScore,
        "dealer_hand" => $state["dealer_hand"], "dealer_score" => $dScore, // REVEAL ALL
        "new_balance" => $_SESSION["tokens"], "winnings" => $winnings
    ]);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid Action."]);
exit;
?>