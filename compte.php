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
        .center-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .large-text {
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }
    </style>
    <?php include('header.php'); ?>
</head>
<body class="d-flex flex-column min-vh-100">

    <div class="container center-content">
        <div class="large-text">
            Brave vous êtes connecté, n'oublie pas de me mettre VERT et de me faire un virement de 75425,74€ d'ici samedi !
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
