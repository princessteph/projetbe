<?php
include('fonctions.php');
session_start();

if (!isset($_SESSION['etu'])) {
    header('Location: ../vraie/login.php');
    exit();
}

if (!isset($_POST['produit'], $_POST['price'], $_POST['quantite'], $_POST['date'])) {
    $_SESSION['message'] = array('type' => 'warning', 'texte' => 'Vérifiez les champs du formulaire.');
    header('Location: ../vraie/vendre.php');
    exit();
}

vente($_SESSION['etu'], $_POST['produit'], $_POST['price'], $_POST['quantite'], $_POST['date'], image_upload('produit'));

$_SESSION['message'] = array('type' => 'success', 'texte' => 'Produit mis en vente avec succès.');
header('Location: ../vraie/accueil.php');
exit();
