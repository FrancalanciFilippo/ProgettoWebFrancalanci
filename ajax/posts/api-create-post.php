<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Devi effettuare il login.']); exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']); exit();
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
// === 🔥 DEBUG FILES IN ARRIVO ===
error_log("=== FILES DEBUG START ===");
error_log("FILES raw: " . print_r($_FILES['materiali'] ?? 'EMPTY', true));
if (!empty($files['name'])) {
    $names = is_array($files['name']) ? $files['name'] : [$files['name']];
    error_log("📁 File ricevuti dall'API: " . count($names));
    foreach ($names as $idx => $n) {
        $err = is_array($files['error']) ? ($files['error'][$idx] ?? -1) : $files['error'];
        $sz = is_array($files['size']) ? ($files['size'][$idx] ?? 0) : $files['size'];
        $tp = is_array($files['type']) ? ($files['type'][$idx] ?? '') : $files['type'];
        $tmp = is_array($files['tmp_name']) ? ($files['tmp_name'][$idx] ?? '') : $files['tmp_name'];
        error_log("  [$idx] name=$n, size=$sz, type=$tp, error=$err, tmp=$tmp, exists=" . (file_exists($tmp) ? 'YES' : 'NO'));
    }
}
error_log("=== FILES DEBUG END ===");
// ================================
// === 1. VALIDAZIONE CAMPI ===
if (empty($postData['titolo'])) {
    echo json_encode(['success' => false, 'message' => 'Il titolo è obbligatorio.']); exit();
}
if ($postData['materia_id'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Seleziona una materia valida.']); exit();
}
if ($postData['max_partecipanti'] < 2 || $postData['max_partecipanti'] > 50) {
    echo json_encode(['success' => false, 'message' => 'I partecipanti devono essere tra 2 e 50.']); exit();
}
if (empty($postData['luogo'])) {
    echo json_encode(['success' => false, 'message' => 'Il luogo dell\'incontro è obbligatorio.']); exit();
}
if (empty($postData['data_inizio'])) {
    echo json_encode(['success' => false, 'message' => 'La data di inizio è obbligatoria.']); exit();
}

// === 2. VALIDAZIONE FILE (CORRETTA PER MULTI-UPLOAD) ===
if (!empty($files['name']) && !empty($files['name'][0])) {  // ← Fix: controlla che ci sia almeno un file valido
    $names = is_array($files['name']) ? $files['name'] : [$files['name']];
    $errors = is_array($files['error']) ? $files['error'] : [$files['error']];
    $sizes = is_array($files['size']) ? $files['size'] : [$files['size']];
    $tmps = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
    $types = is_array($files['type']) ? $files['type'] : [$files['type']];
    
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
    $maxSize = 10 * 1024 * 1024; // 10MB

    foreach ($names as $i => $name) {
        // Salta file vuoti o con nome vuoto
        if (empty($name) || $name === '') continue;
        
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
            echo json_encode(['success' => false, 'message' => $errMessages[$err] ?? 'Errore upload file.']); exit();
        }
        
        if ($size > $maxSize) {
            echo json_encode(['success' => false, 'message' => "Il file \"$name\" è troppo grande (massimo 10MB)."]); exit();
        }
        
        if (!empty($type) && !in_array($type, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => "Formato non valido per \"$name\". Usa solo PDF, JPG, PNG, DOC, TXT."]); exit();
        }
        
        // Fix: controlla che il file temporaneo esista e sia leggibile
        if ($size === 0 || empty($tmp) || !is_readable($tmp)) {
            echo json_encode(['success' => false, 'message' => "Il file \"$name\" non è accessibile o è vuoto."]); exit();
        }
    }
}

// === 3. CHIAMATA AL DATABASE ===
error_log("🔍 DEBUG: Chiamo createPost() con post_id=" . ($postData['titolo'] ?? 'unknown'));

$postId = $dbh->createPost($postData, $files);

error_log("🔍 DEBUG: createPost() ha restituito: $postId");

if ($postId > 0) {
    error_log("✅ DEBUG: Successo, redirect a my_posts.php");
    echo json_encode([
        'success' => true, 
        'message' => 'Post pubblicato con successo!', 
        'post_id' => $postId, 
        'redirect' => 'my_posts.php'
    ]);
} else {
    error_log("❌ DEBUG: Fallimento createPost - controllo errori MySQL");
    // Logga l'ultimo errore MySQL se disponibile
    if (isset($dbh->db) && $dbh->db->error) {
        error_log("💥 MySQL Error: " . $dbh->db->error);
    }
    echo json_encode(['success' => false, 'message' => 'Errore interno durante il salvataggio. Riprova.']);
}
exit();