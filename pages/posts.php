<?php
require_once '../bootstrap.php';
requireLogin();

$templateParams["titolo"] = "SchoolTogether - Posts";
$templateParams["descrizione"] = "Cerca e partecipa a sessioni di studio o progetti di gruppo su SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "posts_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Posts", "active" => true]
];

$templateParams["js"] = [
    "../js/posts.js",
    "../js/tooltips.js"
];

require '../template/base.php';
