<?php
require_once '../bootstrap.php';

session_destroy();

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