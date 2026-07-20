<?php

include('fonctions.php');

modifier_produit($_POST['id_produit'], $_POST['nom'], $_POST['categorie'], $_POST['prix'], isset($_POST['perime']) ? 1 : 0);

header('Location: ../vraie/accueil.php');