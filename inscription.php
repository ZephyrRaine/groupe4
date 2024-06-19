<?php
session_start(); // Démarrer la session (si ce n'est pas déjà fait)
require_once(__DIR__ . '/db.php');



// Initialiser les variables
$email = $password = '';
$error_message = '';

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et valider les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si l'email est déjà utilisé
    $sql_check_email = "SELECT id FROM utilisateurs WHERE email = :email";
    $stmt_check_email = $dbh->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_email->fetch()) {
        $error_message = "Cet email est déjà enregistré.";
    } else {
        // Insérer l'utilisateur dans la base de données
        $sql_insert_user = "INSERT INTO utilisateurs (email, mot_de_passe) VALUES (:email, :password)";
        $stmt_insert_user = $dbh->prepare($sql_insert_user);

        // Hachage du mot de passe
        $hashed_password = $password;

        // Liaison des paramètres et exécution de la requête
        $stmt_insert_user->bindParam(':email', $email);
        $stmt_insert_user->bindParam(':password', $hashed_password);

        if ($stmt_insert_user->execute()) {
            // Redirection vers la page de connexion après l'inscription réussie
            header('Location: connexion.php');
            exit;
        } else {
            $error_message = "Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<header>
    <?php require_once 'header.php'; ?>
</header>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container">
    <h1 class="mt-4">Inscription</h1>
    <form action="inscription.php" method="post" class="mt-4">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
