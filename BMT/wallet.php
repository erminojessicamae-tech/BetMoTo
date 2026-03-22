<?php
// Start the session to access the memory
session_start();

// Give the player a starting balance if they don't have one yet
if (!isset($_SESSION["points"])) {
    $_SESSION["points"] = 1000; // Starting points
}

// Check if the URL has "?add=" in it
if (isset($_GET["add"])) {
    // Add the amount to the session (make sure it's an integer!)
    $_SESSION["points"] += (int)$_GET["add"]; 
}

// Simply output the number (JavaScript will catch this!)
echo $_SESSION["points"];
?>