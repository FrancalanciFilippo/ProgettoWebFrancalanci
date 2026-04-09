<?php
require_once '../bootstrap.php';
requireAdmin();

$userId = (int)($_GET['id'] ?? 0);
$user = $dbh->getUserById($userId);

if (!$user || $user['tipo'] === 'admin') {
    header('Location: admin_users.php');
    exit;
}

$templateParams["titolo"] = "SchoolTogether - Admin: Modifica Utente";
$templateParams["descrizione"] = "Modifica le informazioni dell'utente " . $user['email'];
$templateParams["basePath"] = "../";
$templateParams["main"] = "admin_edit_user_main.php";
$templateParams["editUser"] = $user;
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Admin Dashboard", "url" => "admin.php"],
    ["label" => "Gestione Utenti", "url" => "admin_users.php"],
    ["label" => "Modifica Utente", "active" => true]
];
$templateParams["js"] = ["../js/admin_actions.js"];

require '../template/base.php';
?>
