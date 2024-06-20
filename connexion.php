<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exemple de données utilisateur pour la démonstration
    $users = [
        ['email' => 'user@example.com', 'password' => 'password123']
    ];

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des informations de connexion
    $authenticated = false;
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            $authenticated = true;
            $_SESSION['user'] = $email; // Stocke l'utilisateur dans la session
            break;
        }
    }

    if ($authenticated) {
        // Redirige vers compte.php après une connexion réussie
        header('Location: compte.php');
        exit;
    } else {
        // Redirige vers connexion.php avec un message d'erreur
        header('Location: connexion.php?error=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h1 class="mt-4">Connexion</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger mt-4" role="alert">
            Adresse e-mail ou mot de passe incorrect.
        </div>
    <?php endif; ?>

    <form action="connexion.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
    <p class="mt-3">
        Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous ici</a>.
    </p>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
