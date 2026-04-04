<?php
    require_once('../../bootstrap.php');
    header("Content-Type: application/json");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $posts = $dbh->getAllPosts();
    if ($posts !== null) {
        echo json_encode(["success" => true, "posts" => $posts]);
    } else {
        echo json_encode(["success" => false, "error" => "Impossibile recuperare i posts"]);
    }
?>