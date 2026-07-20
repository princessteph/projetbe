<?php

include('fonctions.php');
session_start();
$_SESSION['etu'] = $_POST['etu'];
$check = check($_POST['etu']);

if ($check) {
    header('Location: ../vrai/accueil.php');
} else {
    header('Location: ../vrai/inscription.php');
}


?>