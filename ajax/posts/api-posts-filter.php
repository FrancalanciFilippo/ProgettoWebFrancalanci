<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

// Raccogli i filtri dai parametri GET
$filters = [
    'exclude_user_id' => $_SESSION['user_id'],
    'not_owner_id'    => $_SESSION['user_id']
];

if (isset($_GET['sort'])) {
    $filters['sort'] = $_GET['sort'];
}
if (isset($_GET['subject']) && !empty($_GET['subject'])) {
    $filters['subject'] = $_GET['subject'];
}
if (isset($_GET['type']) && !empty($_GET['type'])) {
    $filters['type'] = $_GET['type'];
}
if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
    $filters['date_from'] = $_GET['date_from'];
}
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}
if (isset($_GET['show_unavailable'])) {
    $filters['show_unavailable'] = true;
}

try {
    $posts = $dbh->getAllPosts($filters);
    echo json_encode(['success' => true, 'posts' => $posts]);
} catch (Exception $e) {
    error_log("Errore caricamento post filtrati: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nel caricamento dei post.']);
}
exit();