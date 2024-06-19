<?php
sessionstart();
requireonce(__DIR . '/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email est déjà enregistré
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error_message = "Cet email est déjà enregistré.";
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur dans la base de données
            $sql = "INSERT INTO utilisateurs (email, mot_de_passe) VALUES (:email, :password)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $error_message = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
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

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="inscription.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>
</body>
</html>