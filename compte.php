<?php
session_start();
include('db.php');

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .center-button {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .large-text {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include('header.php'); ?>
    <div class="container center-button">
        <div class="large-text">
            Bravo vous êtes connecté, n'oublie pas de me mettre VERT et de me faire un virement de 75425,74€ d'ici samedi !
        </div>
        <form action="logout.php" method="post">
            <button type="submit" class="btn btn-primary">Déconnexion</button>
        </form>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
