<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');


if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.', 'redirect' => 'login.php']);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    $posts = $dbh->getMyPosts($userId);

    echo json_encode([
        'success' => true,
        'posts' => $posts,
        'count' => count($posts)
    ]);
} catch (Exception $e) {
    error_log("Errore caricamento i miei post: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nel caricamento dei post.']);
}
exit();