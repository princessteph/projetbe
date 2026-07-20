<?php
include('fonctions.php');
session_start();

if (!isset($_SESSION['etu'])) {
    header('Location: ../vraie/login.php');
    exit();
}

if (!isset($_POST['nom'], $_POST['categorie'], $_POST['price'])) {
    $_SESSION['message'] = array('type' => 'warning', 'texte' => 'Verifiez les champs du formulaire.');
    header('Location: ../vraie/ajout_produit.php');
    exit();
}

$perime = isset($_POST['perime']) ? 1 : 0; 

ajout_produit($_POST['nom'], $_POST['categorie'], $_POST['price'], $perime); 

$_SESSION['message'] = array('type' => 'success', 'texte' => 'Produit ajoute avec succes.');
header('Location: ../vraie/accueil.php');
exit();