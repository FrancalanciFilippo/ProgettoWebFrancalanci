<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}

$email = trim($_POST['email'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$cognome = trim($_POST['cognome'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';
$bio = trim($_POST['bio'] ?? '');

if (empty($email) || empty($nome) || empty($cognome) || empty($password) || empty($passwordConfirm)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dati mancanti']);
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