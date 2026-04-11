<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dati mancanti: specificare email e password.']);
    exit();
}

try {
    $utente = $dbh->getUserByEmail($email);

    if (!$utente) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Utente non trovato.']);
        exit();
    }

    if (empty($utente['password'])) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore: password non impostata nel database.']);
        exit();
    }

    if (!password_verify($password, $utente['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Password errata.']);
        exit();
    }

    session_regenerate_id(true);

    $_SESSION['user_id'] = $utente['id'];
    $_SESSION['email'] = $utente['email'];
    $_SESSION['nome'] = $utente['nome'];
    $_SESSION['cognome'] = $utente['cognome'];
    $_SESSION['user_type'] = $utente['tipo'];
    $_SESSION['descrizione'] = $utente['descrizione'];

    echo json_encode([
        'success' => true, 
        'message' => 'Login effettuato con successo!',
        'redirect' => $utente['tipo'] === 'admin' ? 'admin.php' : 'profile.php', 
        'userType' => $utente['tipo']
    ]);
} catch (Exception $e) {
    error_log("Errore login: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
}
exit();