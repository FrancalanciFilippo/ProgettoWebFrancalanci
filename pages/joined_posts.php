<?php
require_once '../bootstrap.php';
requireLogin(); // Questa pagina richiede autenticazione

$templateParams["titolo"] = "SchoolTogether - Post a cui partecipi";
$templateParams["descrizione"] = "Visualizza i post e i gruppi di studio a cui stai partecipando su SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "joined_posts_main.php";
$templateParams["hasSidebar"] = true;
$templateParams["sidebarActive"] = "joined_posts";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Profilo", "url" => "profile.php"],
    ["label" => "Post a cui partecipi", "active" => true]
];

require '../template/base.php';
?>
