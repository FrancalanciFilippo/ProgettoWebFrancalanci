<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');


if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.', 'redirect' => 'login.php']);
    exit();
}


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID post non valido.']);
    exit();
}

$postId = (int)$_GET['id'];

try {
    $postInfo = $dbh->getPostInfo($postId);
    if (!$postInfo) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Post non trovato.']);
        exit();
    }

    echo json_encode([
        'success' => true,
        'post' => $postInfo
    ]);
} catch (Exception $e) {
    error_log("Errore caricamento post info: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nel caricamento del post.']);
}
exit();