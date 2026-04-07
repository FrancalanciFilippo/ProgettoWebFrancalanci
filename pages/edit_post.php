<?php
require_once '../bootstrap.php';
requireLogin(); // Questa pagina richiede autenticazione

$templateParams["titolo"] = "SchoolTogether - Modifica Post";
$templateParams["descrizione"] = "Modifica le informazioni del tuo post su SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "edit_post_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Profilo", "url" => "profile.php"],
    ["label" => "I miei post", "url" => "my_posts.php"],
    ["label" => "Modifica Post", "active" => true]
];

require '../template/base.php';
?>
