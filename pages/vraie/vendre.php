<?php
$pageTitle = 'Vendre';
include('../inc/fonctions.php');
include('../inc/header.php');

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$produits = get_all_produit();
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h1 class="h3 mb-0">Vendre un produit</h1>
                        <a href="accueil.php" class="btn btn-outline-secondary btn-sm">Retour</a>
                    </div>
                    <form action="../inc/traitement_vente.php" method="POST" enctype="multipart/form-data">
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
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantite" class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="quantite" name="quantite" step="1" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date dispo</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo du produit (facultatif)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Vendre</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>