<?php
require_once '../bootstrap.php';

// Se l'utente è già loggato, reindirizza alla home
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    header('Location: ../index.php');
    exit;
}

$templateParams["titolo"] = "SchoolTogether - Registrazione";
$templateParams["descrizione"] = "Crea il tuo account SchoolTogether e inizia a condividere la conoscenza con altri studenti.";
$templateParams["basePath"] = "../";
$templateParams["main"] = "signup_main.php";
$templateParams["js"] = ["../js/signup.js"];
$templateParams["breadcrumb"] = [
    ["label" => "Home", "url" => "../index.php"],
    ["label" => "Registrazione", "active" => true]
];

require '../template/base.php';
?>
