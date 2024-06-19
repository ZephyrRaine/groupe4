<?php
require_once 'db.php';

// Sélectionner un utilisateur spécifique (par exemple, l'utilisateur avec l'ID 1)
$userId = 1;

// Vérifier si une requête POST a été envoyée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'ID du produit à partir de la requête POST
    $productId = $_POST['product_id'];
    $quantity = 1; // Quantité à ajouter

    // Ajouter le produit au panier
    $sql = "
        INSERT INTO commandes_produits (id_commande, id_produit, quantite)
        VALUES (:user_id, :product_id, :quantity)
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
}

// Récupérer les commandes de cet utilisateur
$sql = "
    SELECT produits.nom, produits.description, produits.prix, commandes_produits.quantite
    FROM commandes_produits
    JOIN produits ON commandes_produits.id_produit = produits.id
    JOIN commandes ON commandes_produits.id_commande = commandes.id
    WHERE commandes.id_utilisateur = :user_id
";
$stmt = $dbh->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$userOrders = $stmt->fetchAll();
$total = 0;

foreach ($userOrders as $item) {
    $total += $item['prix'] * $item['quantite'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <?php require_once 'header.php'; ?>
        <h1 class="mt-4">Panier</h1>

        <!-- Tableau des commandes -->
        <div id="ordersTable" class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php foreach ($userOrders as $order) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['description']); ?></td>
                            <td><?php echo number_format($order['prix'], 2, ',', ' ') . ' €'; ?></td>
                            <td><?php echo $order['quantite']; ?></td>
                            <td><?php echo number_format($order['prix'] * $order['quantite'], 2, ',', ' ') . ' €'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th><?php echo number_format($total, 2, ',', ' ') . ' €'; ?></th>
                    </tr>
                </tfoot>
            </table>
            <div class="d-flex justify-content-end">
                <a href="checkout.php" class="btn btn-primary">Passer à la caisse</a>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
