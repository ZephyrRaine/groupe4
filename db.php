<?php
$servername = "localhost";
$username = "groupe4";
$password = "groupe4";
$dbname = "groupe4";

try {
    $dbh = new PDO('mysql:host=10.96.16.90;port=3306;dbname=groupe4', 'groupe4', 'groupe4');
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}