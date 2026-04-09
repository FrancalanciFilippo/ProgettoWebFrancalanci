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

$postId = (int)($_POST['post_id'] ?? 0);
$testo = trim($_POST['commento'] ?? '');
$replyToRaw = trim($_POST['reply_to'] ?? '');
$rispostaId = null;

if (!empty($replyToRaw) && is_numeric($replyToRaw)) {
    $rispostaId = (int)$replyToRaw;
}

if ($postId <= 0 || empty($testo)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dati mancanti o non validi.']);
    exit();
}

try {
    $result = $dbh->addComment($postId, $_SESSION['user_id'], $testo, $rispostaId);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Commento aggiunto.',
            'redirect' => 'comments.php?post_id=' . $postId
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
} catch (Exception $e) {
    error_log("Errore aggiunta commento: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();
