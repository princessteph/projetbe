<?php
$pageTitle = 'Accueil';
include('../inc/fonctions.php');
include('../inc/header.php');

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$type = 'info';

if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $message = $_SESSION['message']['texte'];
    unset($_SESSION['message']);
}

$etu = $_SESSION['etu'];
$categories = all_categories();
$produits = produit_membres($etu);

$filterCategorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0;
$filterProduit = isset($_GET['produit']) ? $_GET['produit'] : '';

$produitsFiltres = array();
$produitsNom = array();
foreach ($produits as $produit) {
    $produitsNom[$produit['nom_produit']] = $produit['nom_produit'];

    $ok = true;

    if ($filterCategorie > 0 && (int)$produit['id_categorie'] != $filterCategorie) {
        $ok = false;
    }

    if ($filterProduit != '' && $produit['nom_produit'] != $filterProduit) {
        $ok = false;
    }

    if ($ok) {
        $produitsFiltres[] = $produit;
    }
}

$produits = $produitsFiltres;
?>
<div class="container">
    <?php if ($message != '') { ?>
        <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <div class="produits">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h4 mb-0">Produits à vendre</h2>
            
            <a href="ajout_produit.php" class="btn btn-primary btn-sm">Ajouter un produit</a>
            <a href="modifier_produit.php" class="btn btn-secondary btn-sm">Modifier un produit</a>
        </div>

        <form method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-4">
                <label for="categorie" class="form-label small">Catégorie</label>
                <select id="categorie" name="categorie" class="form-select">
                    <option value="0">Toutes</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= $categorie['id_categorie'] ?>" <?= $filterCategorie > 0 && $filterCategorie === $categorie['id_categorie'] ? 'selected' : '' ?>><?= $categorie['nom_categorie']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label for="produit" class="form-label small">Produit</label>
                <select id="produit" name="produit" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach ($produitsNom as $nomProduit): ?>
                        <option value="<?= htmlspecialchars($nomProduit) ?>" <?= $filterProduit != '' && $filterProduit === $nomProduit ? 'selected' : '' ?>><?= htmlspecialchars($nomProduit) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a href="accueil.php" class="btn btn-outline-secondary">Réinitialiser</a>
            </div>
        </form>

        <?php if (!empty($produits)) { ?>
            <div class="row">
                <?php foreach ($produits as $produit) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <?php
                                $imageProduit = !empty($produit['image']) ? $produit['image'] : 'default.png';
                                $imageProduitChemin = '../assets/img/' . $imageProduit;
                                ?>
                                <div class="product-image-wrapper mb-3">
                                    <img src="<?php echo $imageProduitChemin; ?>" alt="<?php echo $produit['nom_produit']; ?>" class="product-image">
                                </div>
                                <h5 class="card-title"><?php echo $produit['nom_produit']; ?></h5>
                                <p class="card-text">
                                    <strong>Categorie:</strong> <?php echo $produit['nom_categorie']; ?><br>
                                    <strong>Prix unitaire:</strong> <?php echo number_format($produit['prix'], 2); ?> MGA<br>
                                    <strong>Quantite disponible:</strong> <span class="badge bg-success"><?php echo $produit['quantite_dispo']; ?></span><br>
                                    <strong>Date de disponibilite:</strong> <?php echo $produit['date_dispo']; ?><br>
                                    <strong>Vendeur:</strong> <?php echo $produit['nom_membre']; ?>
                                </p>

                                <form action="../inc/traitement_achat.php" method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="id_produit_membre" value="<?php echo $produit['id_produit_membre']; ?>">
                                    <input type="number" name="quantite" class="form-control" required style="width: 90px;" min="1">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-cart"></i> Acheter
                                    </button>
                                </form>

                                <?php if ($produit['quantite_dispo'] <= 0) { ?>
                                    <span class="text-danger">Rupture de stock</span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p>Aucun produit disponible pour le moment.</p>
        <?php } ?>
    </div>
</div>

<?php include('../inc/footer.php'); ?>