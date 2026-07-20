<?php
include('fonctions.php');
session_start();

if (!isset($_SESSION['etu'])) {
    header('Location: ../vraie/login.php');
    exit();
}

if (!isset($_POST['id_produit_membre']) || !isset($_POST['quantite'])) {
    header('Location: ../vraie/accueil.php?msg=erreur_parametres');
    exit();
}

$id_produit_membre = $_POST['id_produit_membre'];
$quantite = (int)$_POST['quantite'];

$resultat = acheter_produit($id_produit_membre, $quantite);

if ($resultat['success']) {
    header('Location: ../vraie/accueil.php?msg=achat_ok');
} else {
    header('Location: ../vraie/accueil.php?msg=stock_insuffisant');
}
exit();