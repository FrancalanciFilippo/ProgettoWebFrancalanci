<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.', 'redirect' => 'login.php']);
    exit();
}

$postId = (int)($_GET['post_id'] ?? 0);
if ($postId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID post mancante.']);
    exit();
}

try {
    $comments = $dbh->getComments($postId);
    $postInfo = $dbh->getPostInfo($postId);
    echo json_encode([
        'success' => true,
        'post_title' => $postInfo ? $postInfo['titolo'] : 'Sconosciuto',
        'comments' => $comments
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore server.']);
}
exit();
