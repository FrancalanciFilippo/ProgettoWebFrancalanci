<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

// Verifica ID post
$postId = (int)($_GET['id'] ?? 0);
if ($postId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID post non valido.']);
    exit();
}

try {
    // Verifica che l'utente sia il proprietario OPPURE un admin
    $post = $dbh->getPostInfo($postId);
    $isOwner = ($post && $post['utente_id'] == $_SESSION['user_id']);
    
    if (!$post || (!$isOwner && !isAdmin())) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Non hai i permessi per vedere i partecipanti di questo post.']);
        exit();
    }

    $participants = $dbh->getPostParticipants($postId);
    echo json_encode([
        'success' => true,
        'participants' => $participants
    ]);
} catch (Exception $e) {
    error_log("Errore API partecipanti: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();
