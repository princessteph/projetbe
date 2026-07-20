<?php
$pageTitle = 'Mes ventes';
include('../inc/fonctions.php');
include('../inc/header.php');

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$total = total_ventes($_SESSION['etu']);
$ventes = get_all_ventes($_SESSION['etu']);
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h1 class="h3 mb-0">Mes ventes</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="fw-bold mb-3">Total des ventes : <?= number_format((float)$total, 2, ',', ' ') ?> MGA</p>
            <?php if (!empty($ventes)) { ?>
                <table class="table table-striped">
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
                                <td><?= $vente['prix']?> MGA</td>
                                <td><?= $vente['quantite'] ?></td>
                                <td><?= $vente['date'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="text-muted">Aucune vente enregistrée pour le moment.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>