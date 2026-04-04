<?php
/* require_once '../bootstrap.php'; */

$templateParams["titolo"] = "SchoolTogether - Login";
$templateParams["descrizione"] = "Accedi al tuo account SchoolTogether per partecipare ai gruppi di studio.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "login_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Login", "active" => true]
];

require '../template/base.php';
?>
