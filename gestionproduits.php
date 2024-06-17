<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des produits</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            align-items : center;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php require_once 'db.php'; ?>
    
<link   href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
<?php require_once(__DIR__ . '/header.php'); ?>
<h2>Ajouter / Modifier / Supprimer des produits</h2>

<?php
class Product {
    public $name;
    public $price;
    public $description;
    public $available;

    public function __construct($name, $price, $description, $available) {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->available = $available;
    }
}

// Simulons une base de données de produits en utilisant un tableau
$products = [];

// Fonction pour ajouter un produit
function addProduct($name, $price, $description, $available) {
    global $products;
    $product = new Product($name, $price, $description, $available);
    $products[] = $product;
}

// Fonction pour modifier un produit (par son index dans le tableau)
function modifyProduct($index, $name, $price, $description, $available) {
    global $products;
    if (isset($products[$index])) {
        $products[$index]->name = $name;
        $products[$index]->price = $price;
        $products[$index]->description = $description;
        $products[$index]->available = $available;
    }
}

// Fonction pour supprimer un produit (par son index dans le tableau)
function deleteProduct($index) {
    global $products;
    if (isset($products[$index])) {
        unset($products[$index]);
        // Réorganiser les clés du tableau après suppression
        $products = array_values($products);
    }
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "add") {
        $name = $_POST["name"];
        $price = floatval($_POST["price"]);
        $description = $_POST["description"];
        $available = isset($_POST["available"]) ? true : false;

        addProduct($name, $price, $description, $available);
    } elseif ($_POST["action"] == "modify") {
        $index = $_POST["index"];
        $name = $_POST["name"];
        $price = floatval($_POST["price"]);
        $description = $_POST["description"];
        $available = isset($_POST["available"]) ? true : false;

        modifyProduct($index, $name, $price, $description, $available);
    } elseif ($_POST["action"] == "delete") {
        $index = $_POST["index"];

        deleteProduct($index);
    }
}
?>

<!-- Formulaire pour ajouter / modifier un produit -->
<form method="post">
    <label>Nom du produit:</label><br>
    <input type="text" name="name" required><br>
    
    <label>Prix:</label><br>
    <input type="number" name="price" step="0.01" required><br>
    
    <label>Description:</label><br>
    <textarea name="description" rows="4" required></textarea><br>
    
    <label>Disponible:</label>
    <input type="checkbox" name="available"><br>
    
    <input type="hidden" name="action" value="add"> <!-- Champ caché pour indiquer l'action -->

    <button type="submit">Ajouter</button>
</form>

<hr>

<!-- Liste des produits actuels -->
<h3>Liste des produits :</h3>
<?php
foreach ($products as $index => $product) {
    echo "<div>";
    echo "<p><strong>Produit " . ($index + 1) . " :</strong><br>";
    echo "Nom : " . $product->name . "<br>";
    echo "Prix : " . $product->price . "<br>";
    echo "Description : " . $product->description . "<br>";
    echo "Disponible : " . ($product->available ? 'Oui' : 'Non') . "</p>";
    
    // Formulaire pour modifier ce produit
    echo '<form method="post">';
    echo '<input type="hidden" name="index" value="' . $index . '">';
    echo '<input type="hidden" name="action" value="modify">';
    echo '<button type="submit">Modifier</button>';
    echo '</form>';
    
    // Formulaire pour supprimer ce produit
    echo '<form method="post">';
    echo '<input type="hidden" name="index" value="' . $index . '">';
    echo '<input type="hidden" name="action" value="delete">';
    echo '<button type="submit">Supprimer</button>';
    echo '</form>';
    
    echo "</div><hr>";
}
?>
<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
