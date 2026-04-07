<?php
require_once '../../bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false, 
        'message' => 'Utente non autenticato.'
    ]);
    exit();
}

echo json_encode([
    'success' => true,
    'data' => [
        'email' => $_SESSION['email'] ?? '',
        'nome' => $_SESSION['nome'] ?? '',
        'cognome' => $_SESSION['cognome'] ?? '',
        'descrizione' => $_SESSION['descrizione'] ?? '',
        'user_type' => $_SESSION['user_type'] ?? 'utente'
    ]
]);
exit();