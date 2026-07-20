<?php
$pageTitle = 'Statistiques';
include('../inc/fonctions.php');
include('../inc/header.php');

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$id_categorie = isset($_GET['id_categorie']) ? (int) $_GET['id_categorie'] : null;
$id_produit = isset($_GET['id_produit']) ? (int) $_GET['id_produit'] : null;

// Niveau 3 : ventes par membre pour un produit donné
if ($id_produit) {
    $produit = get_produit($id_produit);
    if (!$produit) {
        header('Location: statistiques.php');
        exit();
    }
    $categorie = get_categorie($produit['id_categorie']);
    $lignes = stats_par_membre($id_produit);
    $niveau = 'membre';

// Niveau 2 : ventes par produit pour une catégorie donnée
} elseif ($id_categorie) {
    $categorie = get_categorie($id_categorie);
    if (!$categorie) {
        header('Location: statistiques.php');
        exit();
    }
    $lignes = stats_par_produit($id_categorie);
    $niveau = 'produit';

// Niveau 1 : ventes par catégorie
} else {
    $lignes = stats_par_categorie();
    $niveau = 'categorie';
}
?>
<div class="container">
    <h1 class="h3 mb-3">Statistiques des ventes</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item<?= $niveau === 'categorie' ? ' active' : '' ?>">
                <?php if ($niveau === 'categorie') { ?>
                    Par catégorie
                <?php } else { ?>
                    <a href="statistiques.php">Par catégorie</a>
                <?php } ?>
            </li>
            <?php if ($niveau === 'produit' || $niveau === 'membre') { ?>
                <li class="breadcrumb-item<?= $niveau === 'produit' ? ' active' : '' ?>">
                    <?php if ($niveau === 'produit') { ?>
                        <?= htmlspecialchars($categorie['nom_categorie']) ?>
                    <?php } else { ?>
                        <a href="statistiques.php?id_categorie=<?= $categorie['id_categorie'] ?>"><?= htmlspecialchars($categorie['nom_categorie']) ?></a>
                    <?php } ?>
                </li>
            <?php } ?>
            <?php if ($niveau === 'membre') { ?>
                <li class="breadcrumb-item active"><?= htmlspecialchars($produit['nom']) ?></li>
            <?php } ?>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($niveau === 'categorie') { ?>
                <h2 class="h5 mb-3">Ventes par catégorie</h2>
            <?php } elseif ($niveau === 'produit') { ?>
                <h2 class="h5 mb-3">Ventes par produit — <?= htmlspecialchars($categorie['nom_categorie']) ?></h2>
            <?php } else { ?>
                <h2 class="h5 mb-3">Ventes par membre — <?= htmlspecialchars($produit['nom']) ?></h2>
            <?php } ?>

            <?php if (!empty($lignes)) { ?>
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">
                                <?= $niveau === 'categorie' ? 'Catégorie' : ($niveau === 'produit' ? 'Produit' : 'Membre') ?>
                            </th>
                            <th scope="col">Quantité vendue</th>
                            <th scope="col">Total des ventes</th>
                            <?php if ($niveau !== 'membre') { ?><th scope="col"></th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lignes as $ligne) { ?>
                            <tr>
                                <td>
                                    <?php if ($niveau === 'categorie') { ?>
                                        <?= htmlspecialchars($ligne['nom_categorie']) ?>
                                    <?php } else { ?>
                                        <?= htmlspecialchars($ligne['nom']) ?>
                                    <?php } ?>
                                </td>
                                <td><?= (int) $ligne['quantite_vendue'] ?></td>
                                <td><?= number_format((float) $ligne['total_ventes'], 2, ',', ' ') ?> MGA</td>
                                <?php if ($niveau === 'categorie') { ?>
                                    <td><a href="statistiques.php?id_categorie=<?= $ligne['id_categorie'] ?>" class="btn btn-sm btn-outline-primary">Voir les produits</a></td>
                                <?php } elseif ($niveau === 'produit') { ?>
                                    <td><a href="statistiques.php?id_produit=<?= $ligne['id_produit'] ?>" class="btn btn-sm btn-outline-primary">Voir les membres</a></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="text-muted">Aucune donnée disponible.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>