<?php
require_once '../bootstrap.php';
requireLogin(); // Questa pagina richiede autenticazione

$templateParams["titolo"] = "SchoolTogether - Profilo";
$templateParams["descrizione"] = "Visualizza e modifica le informazioni del tuo profilo SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "profile_main.php";
$templateParams["hasSidebar"] = true;
$templateParams["sidebarActive"] = "profile";
$templateParams["js"] = ["../js/profile.js"];
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Profilo", "active" => true]
];

require '../template/base.php';
?>
