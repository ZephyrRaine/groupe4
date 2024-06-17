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

// Exemples d'utilisation :

// Ajouter des produits
addProduct("Produit 1", 19.99, "Description du produit 1", true);
addProduct("Produit 2", 29.99, "Description du produit 2", false);

// Modifier un produit (par exemple, modifier le premier produit ajouté)
modifyProduct(0, "Nouveau nom du Produit 1", 24.99, "Nouvelle description du produit 1", false);

// Supprimer un produit (par exemple, supprimer le deuxième produit ajouté)
deleteProduct(1);

// Afficher les produits actuels
echo "<h2>Liste des produits :</h2>";
foreach ($products as $index => $product) {
    echo "<p><strong>Produit " . ($index + 1) . " :</strong><br>";
    echo "Nom : " . $product->name . "<br>";
    echo "Prix : " . $product->price . "<br>";
    echo "Description : " . $product->description . "<br>";
    echo "Disponible : " . ($product->available ? 'Oui' : 'Non') . "</p>";
}

?>
