<?php
require_once '../bootstrap.php';

$templateParams["titolo"] = "SchoolTogether - Info Post";
$templateParams["descrizione"] = "Visualizza i dettagli della sessione di studio o del progetto di gruppo su SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "post_info_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Posts", "url" => "posts.php"],
    ["label" => "Info Post", "active" => true]
];
$templateParams["js"] = [
    "../js/post_info.js"
];

require '../template/base.php';
?>
