<?php
include('fonctions.php');
session_start();

if (!isset($_SESSION['etu']) || !isset($_POST['name']) || empty($_POST['name'])) {
    header('Location: ../vrai/login.php');
    exit();
}

$etu = $_SESSION['etu'];
$name = $_POST['name'];

if (check($etu)) {
    header('Location: ../vrai/accueil.php');
    exit();
}

inscription($etu, $name, 'default.png');
header('Location: ../vrai/accueil.php');
exit();