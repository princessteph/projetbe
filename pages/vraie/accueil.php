<?php
include('../inc/fonctions.php');
session_start();

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$etu = $_SESSION['etu'];
$produits = produit_membres($etu);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/bootstrap/font/bootstrap-icons.css">
    <title>Accueil</title>
</head>
<body>
    <div class="container">
        <div class="accueil">
            <h1>Bienvenue sur la page d'accueil</h1>
            <a href="deconnexion.php" class="btn btn-danger">Se déconnecter</a>
        </div>

        <div class="produits">
            <h2>Produits des autres membres</h2>
            <?php if (!empty($produits)) { ?>
                <ul class="list-group">
                    <?php foreach ($produits as $produit) {?>
                        <li class="list-group-item">
                            <strong>Nom du produit:</strong> <?php echo $produit['nom_produit']; ?><br>
                            <strong>Catégorie:</strong> <?php echo $produit['nom_categorie']; ?><br>
                            <strong>Prix:</strong> <?php echo number_format($produit['prix'], 2); ?> MGA<br>
                            <strong>Quantité disponible:</strong> <?php echo $produit['quantite_dispo']; ?><br>
                            <strong>Date de disponibilité:</strong> <?php echo $produit['date_dispo']; ?><br>
                            <strong>Proposé par:</strong> <?php echo $produit['nom_membre']; ?>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else{ ?>
                <p>Aucun produit disponible pour le moment.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>