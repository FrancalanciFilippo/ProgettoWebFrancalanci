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

/**
 * Verifica se l'utente è autenticato
 * @return bool true se autenticato, false altrimenti
 */
function isUserLoggedIn(): bool {
    return isset($_SESSION['email']) && !empty($_SESSION['email']);
}

/**
 * Reindirizza al login se l'utente non è autenticato
 * Util per proteggere le pagine che richiedono autenticazione
 */
function requireLogin(): void {
    if (!isUserLoggedIn()) {
        header('Location: ' . (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false ? '../' : '') . 'pages/login.php');
        exit;
    }
}

/**
 * Ritorna i dati dell'utente loggato
 * @return array|null array con i dati dell'utente o null se non loggato
 */
function getLoggedInUser(): ?array {
    if (!isUserLoggedIn()) {
        return null;
    }
    
    return [
        'email' => $_SESSION['email'] ?? null,
        'name' => $_SESSION['nome'] ?? null,
        'surname' => $_SESSION['cognome'] ?? null,
        'password' => $_SESSION['password'] ?? null,
        'description' => $_SESSION['descrizione'] ?? null,
        'type' => $_SESSION['user_type'] ?? null
    ];
}
?>