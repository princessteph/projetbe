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
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Projet BE'; ?></title>
</head>
<body>
<header class="border-bottom bg-light py-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a href="accueil.php" class="text-decoration-none fw-bold text-dark">Projet BE</a>
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

<main class="py-4">
