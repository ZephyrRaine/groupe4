<?php
require_once 'db.php';

// Récupérer les articles de la base de données
$sql = "SELECT * FROM produits
JOIN commandes_produits ON commandes_produits.id_produit = produits.id
JOIN utilisateurs ON ";
$result = $dbh->query($sql);
$cartItems = $result->fetchAll();
$total = 0;

foreach ($cartItems as $item) {
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
        
        <?php if (!empty($cartItems)) : ?>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Nom du produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nom']); ?></td>
                            <td><?php echo number_format($item['prix'], 2, ',', ' ') . ' €'; ?></td>
                            <td><?php echo $item['quantite']; ?></td>
                            <td><?php echo number_format($item['prix'] * $item['quantite'], 2, ',', ' ') . ' €'; ?></td>
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
        <?php else : ?>
            <p class="mt-4">Votre panier est vide.</p>
        <?php endif; ?>
    </div>

    <?php require_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
