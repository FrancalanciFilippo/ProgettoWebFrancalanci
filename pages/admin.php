<?php
require_once '../bootstrap.php';
requireAdmin();

$templateParams["titolo"] = "SchoolTogether - Admin Dashboard";
$templateParams["descrizione"] = "Pannello di controllo amministrativo per la gestione di utenti e contenuti.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "admin_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Admin Dashboard", "active" => true]
];

require '../template/base.php';
?>
