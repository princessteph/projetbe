<?php

include('../inc/fonctions.php');
$total = total_ventes($_SESSION['etu']);
$ventes = get_all_ventes($_SESSION['etu']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/font/bootstrap-icons.css">
    <title>Mes ventes</title>
</head>
<body>
    <div class="container">
        <div class="login">
            <h1>Mes ventes</h1>
            <p>Total des ventes : <?= $total ?> €</p>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Produit</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Quantité</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventes as $vente): ?>
                        <tr>
                            <td><?= $vente['nom'] ?></td>
                            <td><?= $vente['prix'] ?> €</td>
                            <td><?= $vente['quantite'] ?></td>
                            <td><?= $vente['date'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>    
        </div>
    </div>
    <a href="accueil.php">Revenir a l'accueil</a>
</body>
</html>