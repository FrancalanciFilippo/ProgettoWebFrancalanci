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



$targetUserId = (int)($_POST['user_id'] ?? 0);
$nome = trim($_POST['name'] ?? '');
$cognome = trim($_POST['surname'] ?? '');
$email = trim($_POST['email'] ?? '');
$descrizione = trim($_POST['bio'] ?? '');

if ($targetUserId <= 0 || empty($nome) || empty($cognome) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Dati incompleti o non validi.']);
    exit();
}


$targetUser = $dbh->getUserById($targetUserId);
if (!$targetUser || $targetUser['tipo'] === 'admin') {
    echo json_encode(['success' => false, 'message' => 'Non puoi modificare un altro amministratore.']);
    exit();
}

$result = $dbh->updateUserProfile($targetUserId, $email, $nome, $cognome, $descrizione);


echo json_encode($result);
exit();
?>
