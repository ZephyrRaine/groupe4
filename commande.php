<?php
require_once 'db.php';

// Sélectionner un utilisateur spécifique (par exemple, l'utilisateur avec l'ID 1)
$userId = 1;

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

// Traitement de la commande lors de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exemple de traitement du paiement (à adapter selon la méthode de paiement choisie)
    // Ici, on suppose que le paiement est réussi

    // Marquer la commande comme payée
    $sql = "
        UPDATE commandes
        SET statut = 'payée'
        WHERE id_utilisateur = :user_id
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $userId]);

    // Rediriger vers une page de confirmation
    header('Location: confirmation.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <?php require_once 'header.php'; ?>
        <h1 class="mt-4">Passer la commande</h1>

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
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php if (empty($userOrders)) : ?>
                        <tr>
                            <td colspan="4" class="text-center">Votre panier est vide.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($userOrders as $order) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['nom']); ?></td>
                                <td><?php echo number_format($order['prix'], 2, ',', ' ') . ' €'; ?></td>
                                <td><?php echo $order['quantite']; ?></td>
                                <td><?php echo number_format($order['prix'] * $order['quantite'], 2, ',', ' ') . ' €'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th><?php echo number_format($total, 2, ',', ' ') . ' €'; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Formulaire de commande -->
        <form method="post" class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-success">Payer</button>
        </form>
    </div>

    <?php require_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
