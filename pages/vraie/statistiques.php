<?php
$pageTitle = 'Statistiques';
include('../inc/fonctions.php');
include('../inc/header.php');

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$etu = $_SESSION['etu'];
$selectedCategorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0;
$selectedProduit = isset($_GET['produit']) ? (int)$_GET['produit'] : 0;

$sql_categories = "SELECT 
        c.id_categorie,
        c.nom_categorie,
        SUM(v.quantite) AS total_quantite,
        SUM(pm.prix_vente * v.quantite) AS total_montant
    FROM vente v
    JOIN produit_membre pm ON v.id_produit_membre = pm.id_produit_membre
    JOIN produit p ON pm.id_produit = p.id_produit
    JOIN categorie c ON p.id_categorie = c.id_categorie
    JOIN membre m ON pm.id_membre = m.id_membre
    WHERE pm.id_membre = (SELECT id_membre FROM membre WHERE numero_etu = '$etu')
    GROUP BY c.id_categorie, c.nom_categorie
    ORDER BY c.nom_categorie";

$categories_stats = get_all_lines($sql_categories);

$current_categorie = null;
$produits_stats = array();
$current_produit = null;
$membres_stats = array();

if ($selectedCategorie > 0) {
    $current_categorie = get_one_line("SELECT id_categorie, nom_categorie FROM categorie WHERE id_categorie = '$selectedCategorie'");

    $sql_produits = "SELECT 
            p.id_produit,
            p.nom AS nom_produit,
            SUM(v.quantite) AS total_quantite,
            SUM(pm.prix_vente * v.quantite) AS total_montant
        FROM vente v
        JOIN produit_membre pm ON v.id_produit_membre = pm.id_produit_membre
        JOIN produit p ON pm.id_produit = p.id_produit
        JOIN categorie c ON p.id_categorie = c.id_categorie
        JOIN membre m ON pm.id_membre = m.id_membre
        WHERE pm.id_membre = (SELECT id_membre FROM membre WHERE numero_etu = '$etu') AND c.id_categorie = '$selectedCategorie'
        GROUP BY p.id_produit, p.nom
        ORDER BY p.nom";

    $produits_stats = get_all_lines($sql_produits);
}

if ($selectedProduit > 0) {
    $current_produit = get_one_line("SELECT id_produit, nom FROM produit WHERE id_produit = '$selectedProduit'");

    $sql_membres = "SELECT 
            m.id_membre,
            m.nom AS nom_membre,
            m.numero_etu,
            SUM(v.quantite) AS total_quantite,
            SUM(pm.prix_vente * v.quantite) AS total_montant
        FROM vente v
        JOIN produit_membre pm ON v.id_produit_membre = pm.id_produit_membre
        JOIN produit p ON pm.id_produit = p.id_produit
        JOIN membre m ON pm.id_membre = m.id_membre
        WHERE pm.id_membre = (SELECT id_membre FROM membre WHERE numero_etu = '$etu') AND p.id_produit = '$selectedProduit'
        GROUP BY m.id_membre, m.nom, m.numero_etu
        ORDER BY m.nom";

    $membres_stats = get_all_lines($sql_membres);
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h1 class="h3 mb-0">Statistiques</h1>
                        <a href="accueil.php" class="btn btn-outline-secondary btn-sm">Retour</a>
                    </div>

                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="statistiques.php">Toutes les catégories</a></li>
                            <?php if ($current_categorie) { ?>
                                <li class="breadcrumb-item">
                                    <a href="statistiques.php?categorie=<?= (int)$current_categorie['id_categorie'] ?>">
                                        <?= htmlspecialchars($current_categorie['nom_categorie']) ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($current_produit) { ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?= htmlspecialchars($current_produit['nom']) ?>
                                </li>
                            <?php } ?>
                        </ol>
                    </nav>

                    <?php if (!empty($categories_stats)) { ?>
                        <?php if (!$selectedCategorie) { ?>
                            <h5 class="mb-3">Ventes par catégorie</h5>
                            <div class="list-group mb-4">
                                <?php foreach ($categories_stats as $categorie) { ?>
                                    <a href="statistiques.php?categorie=<?= (int)$categorie['id_categorie'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($categorie['nom_categorie']) ?></div>
                                            <small class="text-muted">Cliquez pour voir les produits</small>
                                        </div>
                                        <div class="text-end">
                                            <div><?= (int)$categorie['total_quantite'] ?> unités</div>
                                            <div><?= number_format((float)$categorie['total_montant'], 2, ',', ' ') ?> MGA</div>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-muted">Aucune vente enregistrée pour le moment.</p>
                    <?php } ?>

                    <?php if ($selectedCategorie > 0) { ?>
                        <h5 class="mb-3">Ventes par produit pour <?= htmlspecialchars($current_categorie['nom_categorie']) ?></h5>
                        <?php if (!empty($produits_stats)) { ?>
                            <div class="list-group mb-4">
                                <?php foreach ($produits_stats as $produit) { ?>
                                    <a href="statistiques.php?categorie=<?= (int)$selectedCategorie ?>&produit=<?= (int)$produit['id_produit'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($produit['nom_produit']) ?></div>
                                            <small class="text-muted">Cliquez pour voir les membres</small>
                                        </div>
                                        <div class="text-end">
                                            <div><?= (int)$produit['total_quantite'] ?> unités</div>
                                            <div><?= number_format((float)$produit['total_montant'], 2, ',', ' ') ?> MGA</div>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <p class="text-muted">Aucune vente trouvée pour cette catégorie.</p>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($selectedProduit > 0) { ?>
                        <h5 class="mb-3">Ventes par membre pour <?= htmlspecialchars($current_produit['nom']) ?></h5>
                        <?php if (!empty($membres_stats)) { ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>Membre</th>
                                            <th>Numéro étudiant</th>
                                            <th>Quantité</th>
                                            <th>Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($membres_stats as $membre) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($membre['nom_membre']) ?></td>
                                                <td><?= htmlspecialchars($membre['numero_etu']) ?></td>
                                                <td><?= (int)$membre['total_quantite'] ?></td>
                                                <td><?= number_format((float)$membre['total_montant'], 2, ',', ' ') ?> MGA</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <p class="text-muted">Aucune vente trouvée pour ce produit.</p>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>