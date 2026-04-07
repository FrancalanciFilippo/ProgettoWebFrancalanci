<?php
require_once '../bootstrap.php';

// Effettua il logout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_destroy();

// Mostra la pagina di logout
$templateParams["titolo"] = "SchoolTogether - Logout";
$templateParams["descrizione"] = "Logout da SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "logout_main.php";
$templateParams["hideNav"] = true;
$templateParams["hideFooter"] = true;
$templateParams["hideBreadcrumb"] = true;
$templateParams["css"] = ["../css/logout.css"];

require '../template/base.php';
?>