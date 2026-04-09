<?php
require_once '../bootstrap.php';
requireAdmin();

$templateParams["titolo"] = "SchoolTogether - Gestione Utenti";
$templateParams["descrizione"] = "Lista completa degli utenti registrati. Consente la modifica e l'eliminazione degli account.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "admin_users_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Admin Dashboard", "url" => "admin.php"],
    ["label" => "Gestione Utenti", "active" => true]
];
$templateParams["js"] = ["../js/admin_actions.js"];

// Recupera utenti dal database (esclusi admin)
$templateParams["utenti"] = $dbh->getUsersForAdmin();

require '../template/base.php';
?>
