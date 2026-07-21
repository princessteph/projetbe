<?php
include('fonctions.php');
session_start();

if (!isset($_SESSION['etu'])) {
    header('Location: ../vraie/login.php');
    exit();
}

if (!isset($_POST['id_produit'], $_POST['nom'], $_POST['categorie'], $_POST['prix'])) {
    $_SESSION['message'] = array('type' => 'warning', 'texte' => 'Verifiez les champs du formulaire.');
    header('Location: ../vraie/modifier_produit.php');
    exit();
}

$perime = isset($_POST['perime']) ? 1 : 0; 
$image = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $image = image_upload('produit');
}

modifier_produit($_POST['id_produit'], $_POST['nom'], $_POST['categorie'], $_POST['prix'], $perime, $image);

$_SESSION['message'] = array('type' => 'success', 'texte' => 'Produit modifie avec succes.');
header('Location: ../vraie/accueil.php');
exit();