<?php
include('../inc/fonctions.php');
session_start();

if (!isset($_SESSION['etu'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$type = 'info';

if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'achat_ok':
            $message = 'Achat reussi !';
            $type = 'success';
            break;
        case 'stock_insuffisant':
            $message = 'Stock insuffisant !';
            $type = 'danger';
            break;
        case 'erreur_parametres':
            $message = 'Erreur de parametres.';
            $type = 'warning';
            break;
        case 'produit_introuvable':
            $message = 'Produit introuvable.';
            $type = 'danger';
            break;
        case 'quantite_invalide':
            $message = 'Quantite invalide.';
            $type = 'warning';
            break;
        default:
            $message = '';
    }
}

$etu = $_SESSION['etu'];
$produits = produit_membres($etu);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/font/bootstrap-icons.css">
    <title>Accueil</title>
</head>
<body>
    <div class="container">
        <div class="accueil d-flex justify-content-between align-items-center my-4">
            <h1>Bienvenue sur la page d'accueil</h1>
            <a href="deconnexion.php" class="btn btn-danger">Se deconnecter</a>
        </div>

        <?php if ($message){ ?>
            <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="produits">
            <h2>Produits des autres membres</h2>
            <?php if (!empty($produits)){ ?>
                <div class="row">
                    <?php foreach ($produits as $produit){ ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
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
                                        <input type="number" name="quantite" class="form-control" min="1" max="<?php echo $produit['quantite_dispo']; ?>" value="1" required style="width: 80px;">
                                        <button type="submit" class="btn btn-primary" <?php echo ($produit['quantite_dispo'] <= 0) ? 'disabled' : ''; ?>>
                                            <i class="bi bi-cart"></i> Acheter
                                        </button>
                                    </form>
                                    
                                    <?php if ($produit['quantite_dispo'] <= 0) { ?>
                                        <span class="text-danger">Rupture de stock</span>
                                    <?php }?>
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
    
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>