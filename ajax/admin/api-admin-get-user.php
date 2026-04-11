<?php
require_once '../../bootstrap.php';
requireAdmin();
header('Content-Type: application/json');

$userId = (int)($_GET['id'] ?? 0);

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID utente non valido.']);
    exit();
}

try {
    $user = $dbh->getUserById($userId);
    
    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Utente non trovato.']);
        exit();
    }
    
    // Protezione: non permettere la modifica di admin
    if ($user['tipo'] === 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Non puoi modificare un amministratore.']);
        exit();
    }
    
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Errore nel caricamento dell\'utente.'
    ]);
}
?>
