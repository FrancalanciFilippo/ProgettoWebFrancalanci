<?php
require_once '../bootstrap.php';
requireLogin(); // Questa pagina richiede autenticazione

$templateParams["titolo"] = "SchoolTogether - Crea Post";
$templateParams["descrizione"] = "Crea un nuovo post per organizzare sessioni di studio o cercare membri per progetti universitari.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "create_post_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Crea Post", "active" => true]
];
$templateParams["js"] = [
    "../js/create-post.js"
];

require '../template/base.php';
?>
