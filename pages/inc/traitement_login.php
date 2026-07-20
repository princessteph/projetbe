<?php
include('fonctions.php');
session_start();

if (!isset($_POST['etu']) || empty($_POST['etu'])) {
    header('Location: ../vraie/login.php');
    exit();
}

$_SESSION['etu'] = $_POST['etu'];
$check = check($_POST['etu']);

if ($check) {
    header('Location: ../vraie/accueil.php');
    exit();
} else {
    header('Location: ../vraie/inscription.php');
    exit();
}