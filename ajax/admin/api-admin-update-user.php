<?php
require_once '../../bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accesso negato.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}

// Lettura dati dal form (Multipart/form-data o JSON)
// Dato che usiamo FormData nel JS, leggeremo da $_POST
$targetUserId = (int)($_POST['user_id'] ?? 0);
$nome = trim($_POST['name'] ?? '');
$cognome = trim($_POST['surname'] ?? '');
$email = trim($_POST['email'] ?? '');
$descrizione = trim($_POST['bio'] ?? '');

if ($targetUserId <= 0 || empty($nome) || empty($cognome) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Dati incompleti o non validi.']);
    exit();
}

// Verifica che il target non sia un admin (protezione extra)
$targetUser = $dbh->getUserById($targetUserId);
if (!$targetUser || $targetUser['tipo'] === 'admin') {
    echo json_encode(['success' => false, 'message' => 'Non puoi modificare un altro amministratore.']);
    exit();
}

$result = $dbh->updateUserProfile($targetUserId, $email, $nome, $cognome, $descrizione);

// Nota: NON aggiorniamo la sessione qui perché stiamo modificando un ALTRO utente, non noi stessi.
echo json_encode($result);
exit();
?>
