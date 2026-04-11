<?php
require_once '../bootstrap.php';
requireLogin();

$postId = (int)($_GET['post_id'] ?? 0);
if ($postId <= 0) {
    header("Location: joined_posts.php");
    exit();
}

$templateParams["titolo"] = "SchoolTogether - Commenti";
$templateParams["descrizione"] = "Visualizza e aggiungi commenti ai post su SchoolTogether.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "comments_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Profilo", "url" => "profile.php"],
    ["label" => "Post a cui partecipi", "url" => "joined_posts.php"],
    ["label" => "Commenti", "active" => true]
];

$templateParams["js"] = [
    "../js/comments.js"
];

$templateParams["css"] = [
    "../css/comments.css"
];

require '../template/base.php';
?>