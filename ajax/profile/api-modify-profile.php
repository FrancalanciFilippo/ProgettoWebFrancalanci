<?php

require_once '../../bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Utente non autenticato.']);
    exit();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'JSON non valido.']);
    exit();
}

$nome = trim($data['nome'] ?? '');
$cognome = trim($data['cognome'] ?? '');
$email = trim($data['email'] ?? '');
$descrizione = trim($data['descrizione'] ?? $data['bio'] ?? '');

if (empty($nome) || empty($cognome) || empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nome, cognome ed email sono obbligatori.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email non valida.']);
    exit();
}

$oldEmail = $_SESSION['email'];

try {
    $result = $dbh->updateUserProfile($oldEmail, $email, $nome, $cognome, $descrizione);
    
    if ($result['success']) {
        $_SESSION['nome'] = $nome;
        $_SESSION['cognome'] = $cognome;
        $_SESSION['descrizione'] = $descrizione;
        if ($result['new_email'] && $result['new_email'] !== $oldEmail) {
            $_SESSION['email'] = $result['new_email'];
        }
        
        echo json_encode([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'nome' => $nome,
                'cognome' => $cognome,
                'email' => $result['new_email'] ?? $email,
                'descrizione' => $descrizione
            ],
            'email_changed' => ($result['new_email'] !== $oldEmail)
        ]);
    } else {
        http_response_code($result['message'] === 'Email già registrata.' ? 409 : 400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
    
} catch (Exception $e) {
    error_log("Update profile error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento.']);
}
exit();