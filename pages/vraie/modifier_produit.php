<?php
$pageTitle = 'Modifier un produit';
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
                        <h1 class="h3 mb-0">Modifier un produit</h1>
                        <a href="accueil.php" class="btn btn-outline-secondary btn-sm">Retour</a>
                    </div>
                    <form action="modification.php" method="POST">
                        <div class="mb-3">
                            <label for="produit" class="form-label">Produit</label>
                            <select class="form-control" id="produit" name="produit" required>
                                <?php foreach ($produits as $produit): ?>
                                    <option value="<?= $produit['id_produit'] ?>"><?= $produit['nom'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>