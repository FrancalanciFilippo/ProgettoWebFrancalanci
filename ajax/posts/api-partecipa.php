<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

// Verifica metodo HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}

// Lettura dati
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['post_id']) || !is_numeric($data['post_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID post non valido.']);
    exit();
}

$postId = (int)$data['post_id'];
$email = $_SESSION['email'];

// Esegui partecipazione
try {
    $result = $dbh->partecipa($email, $postId);

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
