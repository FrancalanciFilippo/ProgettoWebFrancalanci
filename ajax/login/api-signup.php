<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica metodo HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}

// Lettura dati
$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'JSON non valido.']);
    exit();
}

if (!isset($data['email'], $data['password'], $data['password_confirm'], $data['nome'], $data['cognome'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dati mancanti.']);
    exit();
}

$email = trim($data['email']);
$nome = trim($data['nome']);
$cognome = trim($data['cognome']);
$password = $data['password'];
$passwordConfirm = $data['password_confirm'];
$bio = trim($data['bio'] ?? '');

// Validazione campi
if (empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email obbligatoria.']);
    exit();
}

if (empty($nome)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nome obbligatorio.']);
    exit();
}

if (empty($cognome)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cognome obbligatorio.']);
    exit();
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password minimo 8 caratteri.']);
    exit();
}

if ($password !== $passwordConfirm) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password non coincidono.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email non valida.']);
    exit();
}

try {
    $result = $dbh->createUser($email, $nome, $cognome, $password, $bio);
    
    if ($result['success']) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Account creato con successo! Reindirizzamento al login...',
            'redirect' => './login.php'
        ]);
    } else {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
} catch (Exception $e) {
    error_log("Errore registrazione: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore durante la registrazione.']);
}
exit();