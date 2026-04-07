<?php
require_once '../../bootstrap.php';

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Dati mancanti: specificare email e password.']);
    exit();
}

$email = trim($data['email']);
$password = $data['password'];

$utente = $dbh->getUserByEmail($email);

if (!$utente) {
    echo json_encode(['success' => false, 'message' => 'Utente non trovato.']);
    exit();
}

if (empty($utente['password']) || $utente['password'] === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Errore: password non impostata nel database.'
        
    ]);
    exit();
}
if (!password_verify($password, $utente['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password errata.']);
    exit();
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_regenerate_id(true);

$_SESSION['email'] = $utente['email'];
$_SESSION['nome'] = $utente['nome'];
$_SESSION['cognome'] = $utente['cognome'];
$_SESSION['user_type'] = $utente['tipo'];
$_SESSION['descrizione'] = $utente['descrizione'];

echo json_encode([
    'success' => true, 
    'redirect' => $utente['tipo'] === 'admin' ? 'admin.php' : 'profile.php', 
    'message' => 'Login effettuato con successo!', 
    'userType' => $utente['tipo']
]);
exit();