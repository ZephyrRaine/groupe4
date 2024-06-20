<?php
session_start();
include('db.php');

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier la connexion à la base de données
    if (!$dbh) {
        die("Erreur de connexion à la base de données.");
    }

    // Préparer et exécuter la requête pour vérifier les informations d'identification
    $stmt = $dbh->prepare("SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Vérifier si un utilisateur correspondant a été trouvé
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $row['id'];
        header("Location: compte.php");
        exit();
    } else {
        $error = "Identifiants incorrects.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include('header.php'); ?>
</head>
<body class="d-flex flex-column min-vh-100">

    <div class="container my-4">
        <h1>Connexion</h1>
        <form action="connexion.php" method="post" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Connexion</button>
        </form>
    </div>

</body>
<?php include('footer.php'); ?>
</html>
