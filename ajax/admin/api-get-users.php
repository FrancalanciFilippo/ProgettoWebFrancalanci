<?php
require_once '../../bootstrap.php';
requireAdmin();
header('Content-Type: application/json');

try {
    $users = $dbh->getUsersForAdmin();
    
    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Errore nel caricamento degli utenti.'
    ]);
}
?>
