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

function isUserLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isUserLoggedIn() && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function requireLogin(): void {
    if (!isUserLoggedIn()) {
        header('Location: ' . (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false ? '../' : '') . 'pages/login.php');
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false ? '../' : '') . 'index.php');
        exit;
    }
}

function getLoggedInUser(): ?array {
    if (!isUserLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'name' => $_SESSION['nome'] ?? null,
        'surname' => $_SESSION['cognome'] ?? null,
        'description' => $_SESSION['descrizione'] ?? null,
        'type' => $_SESSION['user_type'] ?? null
    ];
}
?>