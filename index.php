<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "SchoolTogether - Home";
$templateParams["descrizione"] = "SchoolTogether - La conoscenza cresce solo se condivisa. Partecipa a gruppi di studio o creane uno tu.";
$templateParams["main"] = "home_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "active" => true]
];

require 'template/base.php';
?>