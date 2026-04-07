<?php
require_once '../bootstrap.php';

// Se l'utente è già loggato, reindirizza alla home
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    header('Location: ../index.php');
    exit;
}

$templateParams["titolo"] = "SchoolTogether - Login";
$templateParams["descrizione"] = "Accedi al tuo account SchoolTogether per partecipare ai gruppi di studio.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "login_main.php";
$templateParams["js"] = ["../js/login.js"];
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Login", "active" => true]
];

require '../template/base.php';
?>
