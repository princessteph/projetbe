<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../inc/fonctions.php';

$user = null;
if (!empty($_SESSION['etu'])) {
    $sql = "SELECT * FROM membre WHERE numero_etu = '" . $_SESSION['etu'] . "'";
    $user = get_one_line($sql);
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
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Ctrl+Eat'; ?></title>
</head>
<body>
<header class="site-header">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi-mouse" style="color: #FBEDD6;"></i>
            <a href="accueil.php" class="site-brand">Ctrl+ Eat</a>
        </div>
        
        <nav class="d-flex flex-wrap align-items-center gap-2">
            <?php if (!empty($_SESSION['etu'])) { ?>
                <a href="accueil.php" class="btn btn-outline-primary btn-sm">Accueil</a>
                <a href="vendre.php" class="btn btn-outline-success btn-sm">Vendre</a>
                <a href="mes_ventes.php" class="btn btn-outline-info btn-sm">Mes ventes</a>
                <a href="statistiques.php" class="btn btn-outline-secondary btn-sm">Statistiques</a>
                
                <div class="user-profile ms-2">
                    <img src="../assets/img/<?php echo !empty($user['image_profil']) ? $user['image_profil'] : 'default.png'; ?>" 
                         alt="Photo de profil" 
                         class="user-avatar">
                    <span class="user-name"><?php echo !empty($user['nom']) ? $user['nom'] : 'Utilisateur'; ?></span>
                </div>
                
                <a href="deconnexion.php" class="btn btn-outline-danger btn-sm">Deconnexion</a>
            <?php } ?>
        </nav>
    </div>
</header>

<main class="site-main">