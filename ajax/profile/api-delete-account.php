<?php
require_once '../../bootstrap.php';

header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non supportato.']);
    exit();
}

$email = $_SESSION['email'];

// Elimina l'account dal database
$deleted = $dbh->deleteUser($email);

if ($deleted) {
    // Logout dopo eliminazione
    session_destroy();
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Account eliminato con successo.',
        'redirect' => '../../index.php'
    ]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione dell\'account.']);
}
?>
