<?php
require_once '../bootstrap.php';
requireLogin();

$templateParams["titolo"] = "SchoolTogether - Modifica Password";
$templateParams["descrizione"] = "Modifica la password del tuo account SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "reset_password_main.php";
$templateParams["js"] = ["../js/reset_password.js"];
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Profilo", "url" => "profile.php"],
    ["label" => "Modifica Password", "active" => true]
];
$templateParams["hideNav"] = false;

require '../template/base.php';
?>
