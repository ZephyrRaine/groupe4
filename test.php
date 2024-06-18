<?php
require_once 'db.php'; // Assurez-vous d'inclure correctement votre fichier de connexion

session_start();

// Fonction pour ajouter un produit dans la base de données
function addProduct($name, $price, $categories, $description, $available) {

    // Préparation de la requête SQL sécurisée
    $stmt = $dbh->prepare("INSERT INTO produits (nom, description, prix, stock, id_categorie) VALUES (?, ?, ?, 0, ?)");
    $stmt->bind_param("ssdi", $name, $description, $price, $categories);

    // Exécution de la requête
    if ($stmt->execute()) {
        return true; // Succès
    } else {
        return false; // Échec
    }
}

// Fonction pour modifier un produit dans la base de données
function modifyProduct($id, $name, $price, $categories, $description, $available) {
    global $dbh;

    // Préparation de la requête SQL sécurisée
    $stmt = $dbh->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, id_categorie = ? WHERE id = ?");
    $stmt->bind_param("ssdii", $name, $description, $price, $categories, $id);

    // Exécution de la requête
    if ($stmt->execute()) {
        return true; // Succès
    } else {
        return false; // Échec
    }
}

// Fonction pour supprimer un produit dans la base de données
function deleteProduct($id) {
    global $dbh; // Utilisation de la connexion MySQL globale

    try {
        // Préparation de la requête SQL sécurisée
        $stmt = $dbh->prepare("DELETE FROM produits WHERE id = :id");

        // Liaison des paramètres
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true; // Succès
        } else {
            return false; // Échec
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}


// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? '';
    $index = $_POST["index"] ?? '';
    $name = $_POST["name"] ?? '';
    $price = isset($_POST["price"]) ? floatval($_POST["price"]) : 0.0;
    $categories = $_POST["categories"] ?? '';
    $description = $_POST["description"] ?? '';
    $available = isset($_POST["available"]) ? true : false;

    if ($action == "add") {
        addProduct($name, $price, $categories, $description, $available);
    } elseif ($action == "modify" && is_numeric($index)) {
        modifyProduct($index, $name, $price, $categories, $description, $available);
    } elseif ($action == "delete" && is_numeric($index)) {
        deleteProduct($index);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des produits</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Votre CSS ici */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 8px;
            width: 200px;
            background-color: #fff;
        }
        h2, h3 {
            margin-top: 0;
        }
        .modify-form {
            display: none;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">

        <?php class Product {
                public $name;
                public $price;
                public $categories;
                public $description;
                public $available;

                public function __construct($name, $price, $categories, $description, $available) {
                    $this->name = $name;
                    $this->price = $price;
                    $this->categories = $categories;
                    $this->description = $description;
                    $this->available = $available;
                }
            }
        ?>
        
        <h2>Ajouter / Modifier / Supprimer des produits</h2>

        <!-- Formulaire pour ajouter un produit -->
        <form method="post">
            <label>Nom du produit:</label><br>
            <input type="text" name="name" required><br>
            
            <label>Prix:</label><br>
            <input type="number" name="price" step="0.01" required><br>
            
            <label>Description:</label><br>
            <textarea name="description" rows="4" required></textarea><br>
            
            <label>Catégories:</label><br>
            <input type="text" name="categories" required><br>

            <label>Disponible:</label>
            <input type="checkbox" name="available"><br>
            
            <input type="hidden" name="action" value="add"> <!-- Champ caché pour indiquer l'action -->

            <button type="submit">Ajouter</button>
        </form>

        <hr>

        <!-- Liste des produits actuels -->
        <h3>Liste des produits :</h3>
        <div class="products">
            <?php
            // Récupérer les produits depuis la base de données
            $sql = "SELECT * FROM produits";
            $result = $dbh->query($sql);
            $products = $result->fetchAll();

            if (sizeof($products) > 0) {
                foreach ($products as $row){
                    echo "<div class='product'>";
                    echo "<p><strong>Nom :</strong> " . $row['nom'] . "<br>";
                    echo "<strong>Prix :</strong> " . $row['prix'] . "<br>";
                    echo "<strong>Description :</strong> " . $row['description'] . "<br>";
                    echo "<strong>Catégories :</strong> " . $row['id_categorie'] . "<br>";
                    echo "<strong>Disponible :</strong> " . ($row['stock'] > 0 ? 'Oui' : 'Non') . "</p>";
                    
                    // Bouton pour afficher le formulaire de modification
                    echo '<button onclick="toggleModifyForm(' . $row['id'] . ')" class="btn btn-warning">Modifier</button>';
                    
                    // Formulaire pour modifier ce produit
                    echo '<form method="post" class="modify-form" id="modify-form-' . $row['id'] . '">';
                    echo '<input type="hidden" name="index" value="' . $row['id'] . '">';
                    echo '<label>Nom du produit:</label><br>';
                    echo '<input type="text" name="name" value="' . $row['nom'] . '" required><br>';
                    echo '<label>Prix:</label><br>';
                    echo '<input type="number" name="price" step="0.01" value="' . $row['prix'] . '" required><br>';
                    echo '<label>Description:</label><br>';
                    echo '<textarea name="description" rows="4" required>' . $row['description'] . '</textarea><br>';
                    echo '<label>Catégories:</label><br>';
                    echo '<input type="text" name="categories" value="' . $row['id_categorie'] . '" required><br>';
                    echo '<label>Disponible:</label>';
                    echo '<input type="checkbox" name="available" ' . ($row['stock'] > 0 ? 'checked' : '') . '><br>';
                    echo '<input type="hidden" name="action" value="modify">';
                    echo '<button type="submit">Modifier</button>';
                    echo '</form>';

                    // Formulaire pour supprimer ce produit
                    echo '<form method="post">';
                    echo '<input type="hidden" name="index" value="' . $row['id'] . '">';
                    echo '<input type="hidden" name="action" value="delete">';
                echo '<button type="submit" class="btn btn-danger">Supprimer</button>';
                echo '</form>';
                
                echo "</div>";
            }
        }
            ?>
            </div>
    </div>


    <!-- Votre JavaScript ici si nécessaire -->
</body>
<footer>
        <?php require_once(__DIR__ . '/footer.php'); ?>
</footer>
</html>
        