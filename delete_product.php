<?php
require 'database.php';

if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete related rows in the product_category table
    $sql = "DELETE FROM product_category WHERE product_product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));

    // Delete the product
    $sql = "DELETE FROM product WHERE product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));

    Database::disconnect();
}

header("Location: view_products.php");
?>
