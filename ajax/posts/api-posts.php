<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');


if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.', 'redirect' => 'login.php']);
    exit();
}


$filters = [
    'exclude_user_id' => $_SESSION['user_id'],
    'not_owner_id'    => $_SESSION['user_id']
];

try {
    $posts = $dbh->getAllPosts($filters);
    echo json_encode(['success' => true, 'posts' => $posts]);
} catch (Exception $e) {
    error_log("Errore caricamento post: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nel caricamento dei post.']);
}
exit();