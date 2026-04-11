
<?php
require_once '../bootstrap.php';
requireAdmin();

$postId = (int)($_GET['id'] ?? 0);
$post = $dbh->getPostInfo($postId);

if (!$post) {
    header('Location: admin_posts.php');
    exit;
}

$templateParams["titolo"] = "SchoolTogether - Admin: Modifica Post";
$templateParams["descrizione"] = "Modifica amministrativa del post";
$templateParams["basePath"] = "../";
$templateParams["main"] = "admin_edit_post_main.php";
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Admin Dashboard", "url" => "admin.php"],
    ["label" => "Gestione Post", "url" => "admin_posts.php"],
    ["label" => "Modifica Post", "active" => true]
];
$templateParams["js"] = [
    "../js/admin_edit_post.js"
];

require '../template/base.php';
?>
