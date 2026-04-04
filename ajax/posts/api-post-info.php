<?php
    require_once('../../bootstrap.php');
    header("Content-Type: application/json");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo json_encode(["success" => false, "error" => "ID post non valido"]);
        exit;
    }

    $postId = (int)$_GET['id'];

    $postInfo = $dbh->getPostInfo($postId);
    if ($postInfo === null) {
        echo json_encode(["success" => false, "error" => "Post non trovato"]);
        exit;
    }

    $files = $dbh->getPostFiles($postId);

    echo json_encode([
        "success" => true,
        "post" => $postInfo,
        "files" => $files
    ]);
?>