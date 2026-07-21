<?php
$pageTitle = 'Modifier un produit';
include('../inc/fonctions.php');
include('../inc/header.php');

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$id_produit = $_POST['produit'] ?? null;
$produit = get_produit($id_produit);
$categories = all_categories();
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
                    <form action="../inc/modification.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du produit</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= $produit['nom']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="categorie" class="form-label">Catégorie</label>
                            <select class="form-control" id="categorie" name="categorie" required>
                                <?php foreach ($categories as $categorie) { ?>
                                    <option value="<?= $categorie['id_categorie'] ?>" <?= ($produit['id_categorie'] == $categorie['id_categorie']) ? 'selected' : '' ?>><?= htmlspecialchars($categorie['nom_categorie']) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix de référence</label>
                            <input type="number" class="form-control" id="prix" name="prix" value="<?= $produit['prix_reference']; ?>" step="0.01" required>
                        </div>
                        <?php if (!empty($produit['image'])) { ?>
                            <div class="mb-3">
                                <label class="form-label">Image actuelle</label><br>
                                <img src="../assets/img/<?= htmlspecialchars($produit['image']) ?>" alt="Image actuelle" class="img-thumbnail" style="max-width: 180px;">
                            </div>
                        <?php } ?>
                        <div class="mb-3">
                            <label for="image" class="form-label">Nouvelle image du produit (facultatif)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="perime" name="perime" value="1" <?= (!empty($produit['perime']) && $produit['perime'] == 1) ? 'checked' : '' ?>>
                            <label for="perime" class="form-check-label">Perime</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>