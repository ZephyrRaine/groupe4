<?php
require_once 'db.php';
session_start();

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
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
                } else {
                    // Sinon, réduire la quantité
                    $sql = "
                        UPDATE commandes_produits
                        SET quantite = quantite - :quantity
                        WHERE id_commande = :user_id AND id_produit = :product_id
                    ";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantityToRemove]);
                }
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
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once 'header.php'; ?>
    <div class="container">
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
            <!-- Formulaire de commande -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">Payer</button>
        
            <!-- Modal de paiement -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="payment-form" method="post" action="charge.php">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Informations de paiement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="card-element" class="form-label">Carte de crédit</label>
                                    <div id="card-element"></div>
                                    <div id="card-errors" role="alert"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var stripe = Stripe('pk_test_51PTjn0RubmPDRwsvcT6koUKBdBSte5qyZ53bj7F0SDdMkDRCBMApLWz0KHtRkH3z8a5O3N85yCJwXUAc7aQDU4hL000Lvpsus1');
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>
</body>
</html>
