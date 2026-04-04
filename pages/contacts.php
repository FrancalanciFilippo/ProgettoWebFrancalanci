<?php
/* require_once '../bootstrap.php'; */

$templateParams["titolo"] = "SchoolTogether - Contatti";
$templateParams["descrizione"] = "Contatta il team di SchoolTogether per informazioni, supporto o collaborazioni.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "contacts_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Contatti", "active" => true]
];

require '../template/base.php';
?>
