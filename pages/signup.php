<?php
/* require_once '../bootstrap.php'; */

$templateParams["titolo"] = "SchoolTogether - Registrazione";
$templateParams["descrizione"] = "Crea il tuo account SchoolTogether e inizia a condividere la conoscenza con altri studenti.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "signup_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Registrazione", "active" => true]
];

require '../template/base.php';
?>
