<?php

include('fonctions.php');

vendre_produit($_SESSION['etu'], $_POST['name'], $_POST['category'], $_POST['price'], $_POST['quantite'], $_POST['date']);

header('Location: ../vraie/accueil.php');
?>
