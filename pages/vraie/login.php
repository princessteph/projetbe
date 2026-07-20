<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/bootstrap/font/bootstrap-icons.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="login">
            <h1>Connectez-vous</h1>
            <form action="traitement_login.php" method="POST">
                <div class="mb-3">
                    <label for="etu" class="form-label">ETU</label>
                    <input type="text" class="form-control" id="etu" name="etu" required>
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>