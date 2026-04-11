<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');


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


$nome = trim($_POST['nome'] ?? '');
$cognome = trim($_POST['cognome'] ?? '');
$email = trim($_POST['email'] ?? '');
$descrizione = trim($_POST['descrizione'] ?? $_POST['bio'] ?? '');

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

try {
    $userId = $_SESSION['user_id'];
    $oldEmail = $_SESSION['email'];

    $result = $dbh->updateUserProfile($userId, $email, $nome, $cognome, $descrizione);
    
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
    error_log("Errore aggiornamento profilo: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento.']);
}
exit();