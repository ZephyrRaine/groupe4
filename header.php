<?php
session_start();
require_once(__DIR__ . '/db.php');

// Process login if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Authenticate user
    $user = authenticateUser($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error_message = "Email ou mot de passe incorrect.";
    }
}

function authenticateUser($email, $password) {
    global $dbh;
    $sql = "SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :password";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Titre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Boutique en ligne simplifiée</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="panier.php">Panier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gestionproduits.php">Gestion produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="compte.php">Compte</a>
                </li>
            </ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form class="d-flex" action="logout.php" method="post">
                    <button class="btn btn-outline-danger" type="submit">Déconnexion</button>
                </form>
            <?php else: ?>
                <form class="d-flex" action="" method="post">
                    <input class="form-control me-2" type="email" placeholder="Email" aria-label="Email" name="email" required>
                    <input class="form-control me-2" type="password" placeholder="Mot de passe" aria-label="Mot de passe" name="password" required>
                    <button class="btn btn-outline-success" type="submit">Connexion</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <!-- Rest of your page content goes here -->
</div>

<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
