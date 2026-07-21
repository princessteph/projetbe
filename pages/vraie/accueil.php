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

$id_categorie = isset($_GET['id_categorie']) && $_GET['id_categorie'] !== '' ? (int) $_GET['id_categorie'] : null;
$id_produit = isset($_GET['id_produit']) && $_GET['id_produit'] !== '' ? (int) $_GET['id_produit'] : null;

$categories = all_categories();
$produits_filtre = $id_categorie ? get_produit_by_categorie($id_categorie) : get_all_produit();

$produits = produit_membres($id_categorie, $id_produit);
?>
<div class="container">
    <?php if ($message != '') { ?>
        <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <form method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-auto">
            <select name="id_categorie" class="form-select" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat) { ?>
                    <option value="<?php echo $cat['id_categorie']; ?>" <?php echo ($id_categorie == $cat['id_categorie']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="col-auto">
            <select name="id_produit" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les produits</option>
                <?php foreach ($produits_filtre as $p) { ?>
                    <option value="<?php echo $p['id_produit']; ?>" <?php echo ($id_produit == $p['id_produit']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($p['nom']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
            <?php if ($id_categorie || $id_produit) { ?>
                <a href="accueil.php" class="btn btn-outline-secondary btn-sm">Réinitialiser</a>
            <?php } ?>
        </div>
    </form>


    <div class="produits">
        <h2 class="h4">Produits a vendres</h2>
        <?php if (!empty($produits)) { ?>
            <div class="row">
                <?php foreach ($produits as $produit) { ?>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <?php
                                $imageProduit = !empty($produit['image']) ? $produit['image'] : $imageDefaut;
                                $imageProduitChemin = '../assets/img/' . $imageProduit;
                                ?>
                                <div class="product-image-wrapper mb-3" style="position: relative;">
                                    <?php if (!empty($produit['perime']) && $produit['perime'] == 1) { ?>
                                        <span style="position: absolute; top: 12px; left: 12px; background: linear-gradient(135deg, #CD5C5C 0%, #A0522D 100%); color: #FFF8F0; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; z-index: 10; box-shadow: 0 4px 12px rgba(160,82,45,0.4); letter-spacing: 0.5px;">PERIME</span>
                                    <?php } ?>
                                    <img src="<?php echo $imageProduitChemin; ?>" alt="<?php echo $produit['nom_produit']; ?>" class="product-image">
                                </div>
                                <h5 class="card-title"><?php echo $produit['nom_produit']; ?></h5>
                                <p class="card-text">
                                    <strong>Categorie:</strong> <?php echo $produit['nom_categorie']; ?><br>
                                    <strong>Prix unitaire:</strong> <?php echo number_format($produit['prix'], 2); ?> MGA<br>
                                    <strong>Quantite disponible:</strong> <span class="badge bg-success"><?php echo $produit['quantite_dispo']; ?></span><br>
                                    <strong>Date de disponibilite:</strong> <?php echo $produit['date_dispo']; ?><br>
                                    <strong>Vendeur:</strong> <?php echo $produit['nom_membre']; ?><br>
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