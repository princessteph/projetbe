<?php
include('fonctions.php');
session_start();

if (!isset($_SESSION['etu']) || !isset($_POST['name']) || empty($_POST['name'])) {
    header('Location: ../vraie/login.php');
    exit();
}

$etu = $_SESSION['etu'];
$name = $_POST['name'];

if (check($etu)) {
    header('Location: ../vraie/accueil.php');
    exit();
}

inscription($etu, $name, image_upload());
header('Location: ../vraie/accueil.php');
exit();