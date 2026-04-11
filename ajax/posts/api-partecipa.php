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


$postIdRaw = $_POST['post_id'] ?? null;

if (!isset($postIdRaw) || !is_numeric($postIdRaw)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID post non valido.']);
    exit();
}

$postId = (int)$postIdRaw;
$userId = $_SESSION['user_id'];


try {
    $result = $dbh->partecipa($userId, $postId);

    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => $result['message']]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
} catch (Exception $e) {
    error_log("Errore partecipazione: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();
