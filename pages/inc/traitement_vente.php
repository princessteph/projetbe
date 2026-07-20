<?php

include('fonctions.php');

vente($_SESSION['etu'], $_POST['produit'], $_POST['price'], $_POST['quantite'], $_POST['date']);

header('Location: ../vraie/accueil.php');

?>
