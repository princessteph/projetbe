<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Projet BE'; ?></title>
</head>
<body>
<header class="site-header">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <img src="../assets/img/food-icon.svg" alt="Icône nourriture" class="site-icon">
            <a href="accueil.php" class="site-brand">IT'mikaly</a>
        </div>
        <nav class="d-flex flex-wrap gap-2">
            <?php if (!empty($_SESSION['etu'])): ?>
                <a href="accueil.php" class="btn btn-outline-primary btn-sm">Accueil</a>
                <a href="vendre.php" class="btn btn-outline-success btn-sm">Vendre</a>
                <a href="mes_ventes.php" class="btn btn-outline-info btn-sm">Mes ventes</a>
                <a href="deconnexion.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="site-main">
