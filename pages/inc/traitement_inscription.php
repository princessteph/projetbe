<?php

include('fonctions.php');
session_start();

inscription($_SESSION['etu'], $_POST['name'], 'default.png');
header('Location: ../vrai/accueil.php');