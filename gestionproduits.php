<?php

require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/footer.php');

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

// Initialisation du tableau

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

// Fon
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des produits</title>
</head>
<body>

<h2>Ajouter / Modifier / Supprimer des produits</h2>

<!-- Formulaire pour ajouter / modifier un produit -->
<form method="post">
    <h3>Ajouter / Modifier un produit</h3>
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
<?
