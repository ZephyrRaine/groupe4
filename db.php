<?php
$servername = "localhost";
$username = "groupe4";
$password = "groupe4";
$dbname = "groupe4";

try {
    $dbh = new PDO('mysql:host=10.96.16.90;port=3306;dbname=groupe4', 'groupe4', 'groupe4');
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM table_name";
    $stmt = $dbh->query($sql);

    $rowCount = $stmt->rowCount();
    if ($rowCount > 0) {
        echo "Found " . $rowCount . " product(s).";
    } else {
        echo "No products found.";
    }
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}
