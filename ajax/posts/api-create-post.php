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


$postData = [
    'titolo' => trim($_POST['titolo'] ?? ''),
    'tipo' => $_POST['post_type'] ?? '',
    'materia_id' => (int)($_POST['materia'] ?? 0),
    'max_partecipanti' => (int)($_POST['partecipanti_max'] ?? 0),
    'luogo' => trim($_POST['luogo'] ?? ''),
    'data_inizio' => $_POST['data_inizio'] ?? '',
    'data_fine' => !empty($_POST['data_fine']) ? $_POST['data_fine'] : null,
    'descrizione' => trim($_POST['descrizione'] ?? ''),
    'utente_id' => $_SESSION['user_id']
];


if (empty($postData['titolo']) || empty($postData['materia_id']) || empty($postData['luogo']) || empty($postData['data_inizio'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Titolo, materia, luogo e data di inizio sono obbligatori.']);
    exit();
}
if ($postData['materia_id'] <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Seleziona una materia valida.']);
    exit();
}
if ($postData['max_partecipanti'] < 2 || $postData['max_partecipanti'] > 50) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'I partecipanti devono essere tra 2 e 50.']);
    exit();
}
if (empty($postData['luogo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Il luogo dell\'incontro è obbligatorio.']);
    exit();
}
if (empty($postData['data_inizio'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La data di inizio è obbligatoria.']);
    exit();
}


try {
    $postId = $dbh->createPost($postData);

    if ($postId > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Post pubblicato con successo!', 
            'post_id' => $postId, 
            'redirect' => 'my_posts.php'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore interno durante il salvataggio. Riprova.']);
    }
} catch (Exception $e) {
    error_log("Errore creazione post: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();