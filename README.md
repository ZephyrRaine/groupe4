# groupe4
## Consignes pour créer une boutique en ligne simplifiée

Cette page décrit les étapes nécessaires pour créer une boutique en ligne simplifiée avec les fonctionnalités suivantes :

1. Ajouter, modifier, supprimer des produits.
2. Afficher les produits par catégorie.
3. Ajouter des produits au panier et passer commande.
4. Gestion des utilisateurs (inscription, connexion, rôle administrateur).

### 1. Structure de la base de données

La base de données comprendra les tables suivantes : `utilisateurs`, `categories`, `produits`, `commandes`, et `commandes_produits`.

#### Création des tables

1. **Table `utilisateurs`** :
    ```sql
    CREATE TABLE utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100),
        prenom VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        mot_de_passe VARCHAR(255),
        date_inscription DATE
    );
    ```

2. **Table `categories`** :
    ```sql
    CREATE TABLE categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100)
    );
    ```

3. **Table `produits`** :
    ```sql
    CREATE TABLE produits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255),
        description TEXT,
        prix DECIMAL(10, 2),
        stock INT,
        id_categorie INT,
        FOREIGN KEY (id_categorie) REFERENCES categories(id)
    );
    ```

4. **Table `commandes`** :
    ```sql
    CREATE TABLE commandes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_utilisateur INT,
        date_commande DATE,
        total DECIMAL(10, 2),
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
    );
    ```

5. **Table `commandes_produits`** :
    ```sql
    CREATE TABLE commandes_produits (
        id_commande INT,
        id_produit INT,
        quantite INT,
        prix_unitaire DECIMAL(10, 2),
        PRIMARY KEY (id_commande, id_produit),
        FOREIGN KEY (id_commande) REFERENCES commandes(id),
        FOREIGN KEY (id_produit) REFERENCES produits(id)
    );
    ```

### 2. Insérer des données initiales

1. **Table `utilisateurs`** :
    ```sql
    INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_inscription) VALUES
    ('Dupont', 'Jean', 'jean.dupont@example.com', 'password123', '2023-01-15'),
    ('Martin', 'Claire', 'claire.martin@example.com', 'password456', '2023-02-20'),
    ('Bernard', 'Pierre', 'pierre.bernard@example.com', 'password789', '2023-03-05');
    ```

2. **Table `categories`** :
    ```sql
    INSERT INTO categories (nom) VALUES
    ('Électronique'),
    ('Maison'),
    ('Vêtements');
    ```

3. **Table `produits`** :
    ```sql
    INSERT INTO produits (nom, description, prix, stock, id_categorie) VALUES
    ('Télévision 4K', 'Télévision 4K UHD 55 pouces', 799.99, 10, 1),
    ('Canapé', 'Canapé 3 places en tissu', 499.99, 5, 2),
    ('T-shirt', 'T-shirt 100% coton, taille M', 19.99, 20, 3);
    ```

4. **Table `commandes`** :
    ```sql
    INSERT INTO commandes (id_utilisateur, date_commande, total) VALUES
    (1, '2023-04-01', 819.98),
    (2, '2023-05-10', 519.98);
    ```

5. **Table `commandes_produits`** :
    ```sql
    INSERT INTO commandes_produits (id_commande, id_produit, quantite, prix_unitaire) VALUES
    (1, 1, 1, 799.99),
    (1, 3, 1, 19.99),
    (2, 2, 1, 499.99),
    (2, 3, 1, 19.99);
    ```

### 3. Fonctionnalités principales

#### Ajouter, modifier, supprimer des produits
- Créer des formulaires HTML pour ajouter de nouveaux produits.
- Utiliser des requêtes SQL `INSERT`, `UPDATE`, `DELETE` pour gérer les produits dans la base de données.

#### Afficher les produits par catégorie
- Créer une page PHP qui récupère les produits de la base de données par catégorie et les affiche.

#### Ajouter des produits au panier et passer commande
- Utiliser des sessions PHP pour gérer le panier de l'utilisateur.
- Créer des formulaires pour ajouter des produits au panier.
- Générer une commande à partir des produits dans le panier et l'insérer dans les tables `commandes` et `commandes_produits`.

#### Gestion des utilisateurs (inscription, connexion, rôle administrateur)
- Créer des formulaires pour l'inscription et la connexion des utilisateurs.
- Utiliser des requêtes SQL pour gérer les utilisateurs et vérifier les informations de connexion.
- Implémenter des rôles utilisateurs, comme administrateur, pour gérer les permissions.

### 4. Exemple de script PHP pour l'ajout de produit

```php
<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un produit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];
    $id_categorie = $_POST['id_categorie'];

    $sql = "INSERT INTO produits (nom, description, prix, stock, id_categorie)
    VALUES ('$nom', '$description', '$prix', '$stock', '$id_categorie')";

    if ($conn->query($sql) === TRUE) {
        echo "Nouveau produit ajouté avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
```

### 5. Exemple de script PHP pour afficher les produits par catégorie

```php
<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les produits par catégorie
$id_categorie = $_GET['id_categorie'];
$sql = "SELECT * FROM produits WHERE id_categorie = '$id_categorie'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Afficher les produits
    while($row = $result->fetch_assoc()) {
        echo "Nom: " . $row["nom"]. " - Description: " . $row["description"]. " - Prix: " . $row["prix"]. "€<br>";
    }
} else {
    echo "0 résultats";
}

$conn->close();
?>
```

### 6. Gestion des utilisateurs (inscription et connexion)

#### Inscription

```php
<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inscription utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $date_inscription = date('Y-m-d');

    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_inscription)
    VALUES ('$nom', '$prenom', '$email', '$mot_de_passe', '$date_inscription')";

    if ($conn->query($sql) === TRUE) {
        echo "Inscription réussie";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
```

#### Connexion

```php
<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connexion utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($mot_de_passe, $row['mot_de_passe'])) {
            echo "Connexion réussie";
            // Démarrer une session et enregistrer les informations de l'utilisateur
           

 session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nom'];
        } else {
            echo "Mot de passe incorrect";
        }
    } else {
        echo "Email non trouvé";
    }
}

$conn->close();
?>
```

### Conclusion

Ces consignes vous guident à travers la création d'une boutique en ligne simplifiée en utilisant PHP et MySQL. En suivant ces étapes, vous pouvez ajouter, modifier et supprimer des produits, afficher des produits par catégorie, gérer un panier et des commandes, et permettre aux utilisateurs de s'inscrire et de se connecter.