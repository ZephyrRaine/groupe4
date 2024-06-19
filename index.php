<!DOCTYPE html>
<html>
<header>
    <?php require_once 'header.php'; ?>
</header>
<body>
<title>Liste des produits</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }
    </style>
    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("productTable");
            switching = true;
            dir = "asc";
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }

        function addToCart(productId) {
        // Send an AJAX request to add the product to the cart
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "panier.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response here (e.g., show a success message)
                alert("Product added to cart!");
            }
        };
        xhr.send("product_id=" + productId);
        }
    </script>
    <form method='GET'>
        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        echo "<input type='text' name='search' placeholder='Rechercher...' value='" . htmlspecialchars($search) . "'>";
        ?>
        <input type='submit' value='Rechercher'>
    </form>

    <?php
    require_once 'db.php';

    // Initialize sorting variables
    $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'nom';
    $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

    // Base SQL query
    $sql = "SELECT p.id, p.nom, p.description, p.prix, p.stock, c.nom AS categorie
            FROM produits p
            LEFT JOIN categories c ON p.id_categorie = c.id";

    // Add search condition
    if (!empty($search)) {
        $sql .= " WHERE p.nom LIKE :search";
    }

    // Add sorting
    $sql .= " ORDER BY $sortColumn $sortOrder";

    // Prepare the SQL statement
    $stmt = $dbh->prepare($sql);

    // Bind parameters
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    // Check if there are results
    if ($stmt->rowCount() > 0) {
        echo "<table id='productTable'>";
        echo "<tr>";
        echo "<th onclick='sortTable(0)'>Nom</th>";
        echo "<th onclick='sortTable(1)'>Description</th>";
        echo "<th onclick='sortTable(2)'>Prix</th>";
        echo "<th onclick='sortTable(3)'>Stock</th>";
        echo "<th onclick='sortTable(4)'>Catégorie</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        // Output data of each product
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["nom"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["prix"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["stock"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["categorie"]) . "</td>";
            echo "<td><button onclick='addToCart(" . $row["id"] . ")'>Ajouter au panier</button></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucun produit trouvé.";
    }

    $dbh = null;
    ?>
</body>
<footer>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</footer>
</html>