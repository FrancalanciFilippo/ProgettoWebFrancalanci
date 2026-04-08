<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

// Verifica autenticazione
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato.']);
    exit();
}

// Verifica metodo HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit();
}

// Raccolta dati
$postData = [
    'titolo' => trim($_POST['titolo'] ?? ''),
    'tipo' => $_POST['post_type'] ?? '',
    'materia_id' => (int)($_POST['materia'] ?? 0),
    'max_partecipanti' => (int)($_POST['partecipanti_max'] ?? 0),
    'luogo' => trim($_POST['luogo'] ?? ''),
    'data_inizio' => $_POST['data_inizio'] ?? '',
    'data_fine' => !empty($_POST['data_fine']) ? $_POST['data_fine'] : null,
    'richiede_approvazione' => !empty($_POST['approvazione_richiesta']),
    'descrizione' => trim($_POST['descrizione'] ?? ''),
    'utente_email' => $_SESSION['email']
];
$files = $_FILES['materiali'] ?? [];

// Validazione campi
if (empty($postData['titolo']) || empty($postData['materia_id']) || empty($postData['luogo']) || empty($postData['data_inizio']) || empty($postData['data_fine'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tutti i campi principali (inclusa la data di fine) sono obbligatori.']);
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

// Validazione file
if (!empty($files['name']) && !empty($files['name'][0])) {
    $names = is_array($files['name']) ? $files['name'] : [$files['name']];
    $errors = is_array($files['error']) ? $files['error'] : [$files['error']];
    $sizes = is_array($files['size']) ? $files['size'] : [$files['size']];
    $tmps = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
    $types = is_array($files['type']) ? $files['type'] : [$files['type']];
    
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
    $maxSize = 10 * 1024 * 1024; // 10MB

    foreach ($names as $i => $name) {
        if (empty($name)) continue;
        
        $err = $errors[$i] ?? UPLOAD_ERR_NO_FILE;
        $size = (int)($sizes[$i] ?? 0);
        $type = $types[$i] ?? '';
        $tmp = $tmps[$i] ?? '';
        
        if ($err !== UPLOAD_ERR_OK) {
            $errMessages = [
                UPLOAD_ERR_INI_SIZE => 'Il file supera il limite del server.',
                UPLOAD_ERR_FORM_SIZE => 'Il file supera il limite consentito.',
                UPLOAD_ERR_PARTIAL => 'Il file è stato caricato solo parzialmente.',
                UPLOAD_ERR_NO_FILE => 'Nessun file selezionato.',
                UPLOAD_ERR_NO_TMP_DIR => 'Errore server temporaneo.',
                UPLOAD_ERR_CANT_WRITE => 'Errore scrittura su disco.',
                UPLOAD_ERR_EXTENSION => 'Caricamento bloccato dal server.'
            ];
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $errMessages[$err] ?? 'Errore upload file.']);
            exit();
        }
        
        if ($size > $maxSize) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Il file \"$name\" è troppo grande (massimo 10MB)."]);
            exit();
        }
        
        if (!empty($type) && !in_array($type, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Formato non valido per \"$name\". Usa solo PDF, JPG, PNG, DOC, TXT."]);
            exit();
        }
        
        if ($size === 0 || empty($tmp) || !is_readable($tmp)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Il file \"$name\" non è accessibile o è vuoto."]);
            exit();
        }
    }
}

// Creazione post
try {
    $postId = $dbh->createPost($postData, $files);

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