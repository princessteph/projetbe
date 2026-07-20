<?php
session_start();
if (!isset($_SESSION['etu'])) {
    header('Location: vraie/login.php');
    exit();
} else {
    $etu = $_SESSION['etu'];
    header('Location: vraie/accueil.php');
    exit();
}
