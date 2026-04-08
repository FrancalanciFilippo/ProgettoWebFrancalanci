<?php
require_once '../../bootstrap.php';
header('Content-Type: application/json');

try {
    $materie = $dbh->getAllMaterie();
    echo json_encode(['success' => true, 'materie' => $materie]);
} catch (Exception $e) {
    error_log("Errore caricamento materie: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nel caricamento delle materie.']);
}
exit();