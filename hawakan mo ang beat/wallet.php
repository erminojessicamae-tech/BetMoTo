<?php
// Start the session to access the memory
session_start();

// Give the player a starting balance if they don't have one yet
if (!isset($_SESSION["money"])) {
    $_SESSION["money"] = 1000; // Starting money
}

// Simply output the number (JavaScript will catch this!)
echo $_SESSION["money"];
?>