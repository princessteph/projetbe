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
$produits = produit_membres($etu);
    ?>
<div class="container">
    <?php if ($message != '') { ?>
        <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <div class="produits">
        <h2 class="h4">Produits a vendres</h2>
        <?php if (!empty($produits)) { ?>
            <div class="row">
                <?php foreach ($produits as $produit) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($produit['nom_produit']); ?></h5>
                                <p class="card-text">
                                    <strong>Catégorie:</strong> <?php echo htmlspecialchars($produit['nom_categorie']); ?><br>
                                    <strong>Prix unitaire:</strong> <?php echo number_format($produit['prix'], 2); ?> MGA<br>
                                    <strong>Quantité disponible:</strong> <span class="badge bg-success"><?php echo $produit['quantite_dispo']; ?></span><br>
                                    <strong>Date de disponibilité:</strong> <?php echo htmlspecialchars($produit['date_dispo']); ?><br>
                                    <strong>Vendeur:</strong> <?php echo htmlspecialchars($produit['nom_membre']); ?>
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