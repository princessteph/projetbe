<?php
include('fonctions.php');
session_start();

if (!isset($_SESSION['etu'])) {
    header('Location: ../vraie/login.php');
    exit();
}

if (!isset($_POST['id_produit_membre']) || $_POST['id_produit_membre'] == '' || !isset($_POST['quantite'])) {
    $_SESSION['message'] = array('type' => 'warning', 'texte' => 'Erreur de parametres.');
    header('Location: ../vraie/accueil.php');
    exit();
}

$id_produit_membre = $_POST['id_produit_membre'];
$quantite = (int)$_POST['quantite'];

$resultat = acheter_produit($id_produit_membre, $quantite);

$_SESSION['message'] = $resultat;

header('Location: ../vraie/accueil.php');
exit();
