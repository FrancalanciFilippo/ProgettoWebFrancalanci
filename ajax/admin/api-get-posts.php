<?php
require_once '../../bootstrap.php';
requireAdmin();
header('Content-Type: application/json');

try {
    $posts = $dbh->getPostsForAdmin();
    
    echo json_encode([
        'success' => true,
        'posts' => $posts
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Errore nel caricamento dei post.'
    ]);
}
?>
