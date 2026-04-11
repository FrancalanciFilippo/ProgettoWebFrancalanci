<?php
require_once '../bootstrap.php';
requireLogin();

$templateParams["titolo"] = "SchoolTogether - I miei post";
$templateParams["descrizione"] = "Visualizza e gestisci i post e i gruppi di studio creati da te su SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "my_posts_main.php";
$templateParams["hasSidebar"] = true;
$templateParams["sidebarActive"] = "my_posts";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Profilo", "url" => "profile.php"],
    ["label" => "I miei post", "active" => true]
];
$templateParams["js"] = [
    "../js/my_posts.js"
];

require '../template/base.php';
?>
