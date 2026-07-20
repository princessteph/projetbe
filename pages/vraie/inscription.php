<?php
$pageTitle = 'Inscription';
include('../inc/header.php');
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-3">Inscrivez-vous</h1>
                    <form action="../inc/traitement_inscription.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo de profil (facultatif)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                        <a href="login.php" class="btn btn-outline-secondary ms-2">Retour</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>