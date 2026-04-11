<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');


if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.', 'redirect' => 'login.php']);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}


$postId = (int)($_POST['post_id'] ?? 0);
$targetUserId = (int)($_POST['user_id'] ?? 0);

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
