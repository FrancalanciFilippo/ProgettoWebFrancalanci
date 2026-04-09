<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

// Lettura dati
$data = json_decode(file_get_contents('php://input'), true);
$postId = (int)($data['post_id'] ?? 0);
$targetUserId = (int)($data['user_id'] ?? 0);

if ($postId <= 0 || $targetUserId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Parametri mancanti o non validi.']);
    exit();
}

try {
    $result = $dbh->removeParticipantByOwner($_SESSION['user_id'], $postId, $targetUserId);
    
    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => $result['message']]);
    } else {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
} catch (Exception $e) {
    error_log("Errore API rimozione partecipante: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();
