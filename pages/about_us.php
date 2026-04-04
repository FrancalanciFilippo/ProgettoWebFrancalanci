<?php
/* require_once '../bootstrap.php'; */

$templateParams["titolo"] = "SchoolTogether - Chi siamo";
$templateParams["descrizione"] = "Scopri chi siamo, la nostra missione e il team dietro SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "about_us_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Chi siamo", "active" => true]
];

require '../template/base.php';
?>
