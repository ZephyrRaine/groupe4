<?php
require_once 'db.php';

// Sélectionner un utilisateur spécifique (par exemple, l'utilisateur avec l'ID 1)
$userId = 1;

// Vérifier si une requête POST a été envoyée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $quantityToRemove = intval($_POST['quantity']);
            // Vérifier la quantité actuelle du produit dans le panier
            $sql = "
                SELECT quantite FROM commandes_produits
                WHERE id_commande = :user_id AND id_produit = :product_id
            ";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            $productInCart = $stmt->fetch();

            if ($productInCart) {
                if ($productInCart['quantite'] <= $quantityToRemove) {
                    // Si la quantité à enlever est supérieure ou égale à la quantité actuelle, supprimer l'entrée
                    $sql = "
                        DELETE FROM commandes_produits
                        WHERE id_commande = :user_id AND id_produit = :product_id
                    ";
                } else {
                    // Sinon, réduire la quantité
                    $sql = "
                        UPDATE commandes_produits
                        SET quantite = quantite - :quantity
                        WHERE id_commande = :user_id AND id_produit = :product_id
                    ";
                }

                $stmt = $dbh->prepare($sql);
                $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantityToRemove]);
            }
        } else {
            // Ajouter le produit au panier
            // Vérifier si le produit est déjà dans le panier
            $sql = "
                SELECT * FROM commandes_produits
                WHERE id_commande = :user_id AND id_produit = :product_id
            ";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            $productInCart = $stmt->fetch();

            if ($productInCart) {
                // Si le produit est déjà dans le panier, augmenter la quantité
                $sql = "
                    UPDATE commandes_produits
                    SET quantite = quantite + 1
                    WHERE id_commande = :user_id AND id_produit = :product_id
                ";
            } else {
                // Si le produit n'est pas encore dans le panier, l'ajouter
                $sql = "
                    INSERT INTO commandes_produits (id_commande, id_produit, quantite)
                    VALUES (:user_id, :product_id, 1)
                ";
            }

            $stmt = $dbh->prepare($sql);
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        }
    }
}

// Récupérer les commandes de cet utilisateur
$sql = "
    SELECT produits.nom, produits.description, produits.prix, commandes_produits.id_produit, commandes_produits.quantite
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

// Récupérer tous les produits disponibles
$sql = "SELECT * FROM produits";
$products = $dbh->query($sql)->fetchAll();
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
            <h2>Votre panier</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php if (empty($userOrders)) : ?>
                        <tr>
                            <td colspan="5" class="text-center">Votre panier est vide.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($userOrders as $order) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['nom']); ?></td>
                                <td><?php echo number_format($order['prix'], 2, ',', ' ') . ' €'; ?></td>
                                <td><?php echo $order['quantite']; ?></td>
                                <td><?php echo number_format($order['prix'] * $order['quantite'], 2, ',', ' ') . ' €'; ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?php echo $order['id_produit']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $order['quantite']; ?>" class="form-control d-inline" style="width: 80px;">
                                        <button type="submit" class="btn btn-danger">Vider</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
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
