<?php
    require_once('../../bootstrap.php');
    header("Content-Type: application/json");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Raccogli i filtri dai parametri GET
    $filters = [];

    if (isset($_GET['sort'])) {
        $filters['sort'] = $_GET['sort'];
    }

    if (isset($_GET['subject']) && !empty($_GET['subject'])) {
        $filters['subject'] = $_GET['subject'];
    }

    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $filters['type'] = $_GET['type'];
    }

    if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
        $filters['date_from'] = $_GET['date_from'];
    }

    if (isset($_GET['no_auth'])) {
        $filters['no_auth'] = true;
    }

    if (isset($_GET['show_unavailable'])) {
        $filters['show_unavailable'] = true;
    }

    $posts = $dbh->getAllPosts($filters);
    if ($posts !== null) {
        echo json_encode(["success" => true, "posts" => $posts]);
    } else {
        echo json_encode(["success" => false, "error" => "Impossibile recuperare i posts"]);
    }
?>