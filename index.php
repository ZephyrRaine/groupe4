<?php
// Include the db.php file
require_once '/c:/xampp/htdocs/colin/groupe4/db.php';

// Fetch products from the database
$sql = "SELECT p.nom, p.description, p.prix, p.stock, c.nom AS categorie
    FROM produits p
    INNER JOIN categories c ON p.id_categorie = c.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each product
    while ($row = $result->fetch_assoc()) {
    echo "Nom: " . $row["nom"] . "<br>";
    echo "Description: " . $row["description"] . "<br>";
    echo "Prix: " . $row["prix"] . "<br>";
    echo "Stock: " . $row["stock"] . "<br>";
    echo "Catégorie: " . $row["categorie"] . "<br>";
    echo "<br>";
    }
} else {
    echo "Aucun produit trouvé.";
}

$conn->close();
?>
