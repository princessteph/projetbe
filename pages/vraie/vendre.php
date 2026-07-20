<?php

include('fonctions.php');
$produits = get_all_produit();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/font/bootstrap-icons.css">
    <title>Vendre</title>
</head>
<body>
    <div class="container">
        <div class="login">
            <h1>Vendre un produit</h1>
            <form action="../inc/traitement_vente.php" method="POST">
                <div class="mb-3">
                    <label for="produit" class="form-label">Produit</label>
                    <select class="form-control" id="produit" name="produit" required>
                        <?php foreach ($produits as $produit): ?>
                            <option value="<?= $produit['id_produit'] ?>"><?= $produit['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Prix</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="quantite" class="form-label">Quantité</label>
                    <input type="number" class="form-control" id="quantite" name="quantite" step="1" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date dispo</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <button type="submit" class="btn btn-primary">Vendre</button>
            </form>
        </div>
    </div>
</body>
</html>