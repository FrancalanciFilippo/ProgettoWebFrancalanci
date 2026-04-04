<?php
    require_once('../../bootstrap.php');

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        http_response_code(400);
        echo "ID file non valido";
        exit;
    }

    $fileId = (int)$_GET['id'];

    // Ottieni il file dal database
    $file = $dbh->getFileForDownload($fileId);

    if ($file === null) {
        http_response_code(404);
        echo "File non trovato";
        exit;
    }

    // Imposta gli header per il download
    header('Content-Type: ' . ($file['tipo'] ?: 'application/octet-stream'));
    header('Content-Disposition: attachment; filename="' . $file['nome'] . '"');
    header('Content-Length: ' . $file['dimensione_byte']);
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    // Invia il contenuto del file
    echo $file['contenuto'];
    exit;
?>