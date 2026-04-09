<?php
require_once '../../bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}

// ID post dall'URL o dal body
$postId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($postId <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID post non valido.']);
    exit();
}

// Dati da aggiornare (solo campi consentiti)
$postData = [
    'luogo' => trim($_POST['luogo'] ?? ''),
    'data_inizio' => $_POST['data_inizio'] ?? '',
    'data_fine' => !empty($_POST['data_fine']) ? $_POST['data_fine'] : null,
    'descrizione' => trim($_POST['descrizione'] ?? '')
];

// Validazioni minime
if (empty($postData['luogo']) || empty($postData['data_inizio'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Luogo e data di inizio sono obbligatori.']);
    exit();
}

// File nuovi da caricare
$filesToAdd = $_FILES['materiali'] ?? [];

// ID file da eliminare (inviati come stringa separata da virgole dal JS)
$fileIdsToDelete = [];
if (!empty($_POST['delete_files']) && is_string($_POST['delete_files'])) {
    $fileIdsToDelete = array_map('intval', explode(',', $_POST['delete_files']));
}

// ID partecipanti da rimuovere (inviati come stringa separata da virgole dal JS)
$participantIdsToKick = [];
if (!empty($_POST['delete_participants']) && is_string($_POST['delete_participants'])) {
    $participantIdsToKick = array_map('intval', explode(',', $_POST['delete_participants']));
}

try {
    $result = $dbh->updatePost(
        $postId,
        $_SESSION['user_id'],
        $postData,
        $filesToAdd,
        $fileIdsToDelete,
        $participantIdsToKick
    );
    
    if ($result['success']) {
        $redirect = $_POST['redirect_url'] ?? (isAdmin() ? 'admin_posts.php' : 'my_posts.php');
        echo json_encode([
            'success' => true,
            'message' => $result['message'],
            'redirect' => $redirect
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
    
} catch (Exception $e) {
    error_log("Update post error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();