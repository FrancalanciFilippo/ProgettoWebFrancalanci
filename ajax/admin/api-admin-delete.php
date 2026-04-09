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

// Lettura dati JSON
$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'] ?? ''; // 'user' o 'post'
$id = (int)($data['id'] ?? 0);

if ($id <= 0 || !in_array($type, ['user', 'post'])) {
    echo json_encode(['success' => false, 'message' => 'Parametri non validi.']);
    exit();
}

$result = ['success' => false, 'message' => 'Tipo non gestito.'];

switch ($type) {
    case 'user':
        $result = $dbh->deleteUserByAdmin($id);
        break;
    case 'post':
        $result = $dbh->deletePostByAdmin($id);
        break;
}

echo json_encode($result);
exit();
?>
