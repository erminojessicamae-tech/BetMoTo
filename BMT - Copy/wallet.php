<?php
// Start the session to access the memory
session_start();

// Give the player a starting balance if they don't have one yet
if (!isset($_SESSION["tokens"])) {
    $_SESSION["tokens"] = 1000; // Starting tokens
}

// Check if the URL has "?add=" in it
if (isset($_GET["add"])) {
    // Add the amount to the session (make sure it's an integer!)
    $_SESSION["tokens"] += (int)$_GET["add"]; 
}

// Simply output the number (JavaScript will catch this!)
echo $_SESSION["tokens"];
?>