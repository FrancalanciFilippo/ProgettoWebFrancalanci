<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} else {
    error_log("Sessione già attiva con ID: " . session_id());
}

require_once "db/database.php";

$dbh = new DatabaseHelper("localhost", "root", "", "SchoolTogether", "3306");

if (!isset($dbh)) {
    echo "Errore: connessione al database non definita.";
    exit;
}
?>