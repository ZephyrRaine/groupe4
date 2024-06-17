<?php
$servername = "localhost";
$username = "groupe4";
$password = "groupe4";
$dbname = "groupe4";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

echo "Connexion à la base de données réussie";