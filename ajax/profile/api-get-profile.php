<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
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