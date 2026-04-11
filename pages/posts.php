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

$filters = [];
if (isset($_GET['sort'])) $filters['sort'] = $_GET['sort'];
if (isset($_GET['subject']) && !empty($_GET['subject'])) $filters['subject'] = $_GET['subject'];
if (isset($_GET['type']) && !empty($_GET['type'])) $filters['type'] = $_GET['type'];
if (isset($_GET['date_from']) && !empty($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
if (isset($_GET['no_auth'])) $filters['no_auth'] = true;
if (isset($_GET['show_unavailable'])) $filters['show_unavailable'] = true;

$templateParams["filters"] = $filters;

$templateParams["js"] = [
    "../js/posts.js",
    "../js/posts_filter.js",
    "../js/tooltips.js"
];

require '../template/base.php';
