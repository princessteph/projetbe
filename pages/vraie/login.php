<?php
$pageTitle = 'Connexion';
include('../inc/header.php');
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-3">Connectez-vous</h1>
                    <form action="../inc/traitement_login.php" method="POST">
                        <div class="mb-3">
                            <label for="etu" class="form-label">ETU</label>
                            <input type="text" class="form-control" id="etu" name="etu" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>