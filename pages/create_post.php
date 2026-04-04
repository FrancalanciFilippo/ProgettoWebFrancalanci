<?php
/* require_once '../bootstrap.php'; */

$templateParams["titolo"] = "SchoolTogether - Crea Post";
$templateParams["descrizione"] = "Crea un nuovo post per organizzare sessioni di studio o cercare membri per progetti universitari.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "create_post_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Crea Post", "active" => true]
];

require '../template/base.php';
?>
