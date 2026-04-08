<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.', 'redirect' => 'login.php']);
    exit();
}

$postId = (int)($_GET['id'] ?? 0);

if ($postId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID post non valido.']);
    exit();
}

try {
    $result = $dbh->lasciaPost($_SESSION['email'], $postId);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Hai abbandonato il post con successo.',
            'redirect' => 'joined_posts.php'
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
} catch (Exception $e) {
    error_log("Errore abbandono post (API): " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();
