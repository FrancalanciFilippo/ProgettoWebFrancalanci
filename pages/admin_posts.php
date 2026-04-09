<?php
require_once '../bootstrap.php';
requireAdmin();

$templateParams["titolo"] = "SchoolTogether - Gestione Post";
$templateParams["descrizione"] = "Visualizza e gestisci tutti i post pubblicati sulla piattaforma.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "admin_posts_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Admin Dashboard", "url" => "admin.php"],
    ["label" => "Gestione Post", "active" => true]
];
$templateParams["js"] = ["../js/admin_actions.js"];

// Recupera tutti i post
$templateParams["posts"] = $dbh->getPostsForAdmin();

require '../template/base.php';
?>
