<?php
// À inclure APRÈS session_start()
if (!isset($_SESSION["id"]) || $_SESSION["type"] != 1) {
    header("Location: index.php");
    exit;
}
?>