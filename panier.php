<?php
require_once 'db.php';

// Récupérer les utilisateurs
$sql = "SELECT * FROM utilisateurs";
$result = $dbh->query($sql);
$users = $result->fetchAll();

// Récupérer les commandes de tous les utilisateurs
$userOrders = [];
foreach ($users as $user) {
    $sql = "
        SELECT produits.nom, description, produits.prix, commandes_produits.quantite
        FROM commandes_produits
        JOIN produits ON commandes_produits.id_produit = produits.id
        JOIN commandes ON commandes_produits.id_commande = commandes.id
        WHERE commandes.id_utilisateur = :user_id
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user['id']]);
    $userOrders[$user['id']] = $stmt->fetchAll();
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

        <!-- Boutons pour sélectionner un utilisateur -->
        <div class="btn-group mt-4" role="group">
            <?php foreach ($users as $user) : ?>
                <button class="btn btn-secondary" onclick="showOrders(<?php echo $user['id']; ?>)">
                    <?php echo htmlspecialchars($user['prenom']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Tableau des commandes -->
        <div id="ordersTable" class="mt-4" style="display: none;">
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
                    <!-- Les lignes seront insérées dynamiquement ici -->
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th id="ordersTableTotal"></th>
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
    <script>
        // Les commandes de chaque utilisateur en format JavaScript
        const userOrders = <?php echo json_encode($userOrders); ?>;

        function showOrders(userId) {
            const orders = userOrders[userId];
            const ordersTableBody = document.getElementById('ordersTableBody');
            const ordersTableTotal = document.getElementById('ordersTableTotal');
            let total = 0;

            ordersTableBody.innerHTML = ''; // Effacer les lignes précédentes

            orders.forEach(order => {
                const subtotal = order.prix * order.quantite;
                total += subtotal;
                console.log(order);
                const row = `<tr>
                    <td>${order.description}</td>
                    <td>${order.prix.replace('.', ',')} €</td>
                    <td>${order.quantite}</td>
                    <td>${subtotal.toString().replace('.', ',')} €</td>
                </tr>`;
                ordersTableBody.insertAdjacentHTML('beforeend', row);
            });

            ordersTableTotal.textContent = total.toFixed(2).replace('.', ',') + ' €';
            document.getElementById('ordersTable').style.display = 'block';
        }
    </script>
</body>
</html>
