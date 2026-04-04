<?php
/* require_once '../bootstrap.php'; */

$templateParams["titolo"] = "SchoolTogether - Richieste";
$templateParams["descrizione"] = "Gestisci le richieste di partecipazione inviate e ricevute su SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "requests_main.php";
$templateParams["hasSidebar"] = true;
$templateParams["sidebarActive"] = "requests";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Profilo", "url" => "profile.php"],
    ["label" => "Richieste", "active" => true]
];

require '../template/base.php';
?>
