<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    $posts = $dbh->getJoinedPosts($userId);

    echo json_encode([
        'success' => true,
        'posts' => $posts,
        'count' => count($posts)
    ]);
} catch (Exception $e) {
    error_log("Errore caricamento post partecipati: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nel caricamento dei post.']);
}
exit();
