<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mini Réseau Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Bienvenue sur Mini Réseau Social</h1>
    <h2>Inscription</h2>
    <form action="register.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <h2>Connexion</h2>
    <form action="login.php" method="post">
        <div class="mb-3">
            <label for="login_username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="login_username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="login_password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="login_password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>
</body>
</html>
