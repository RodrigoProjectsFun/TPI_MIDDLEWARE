<?php
require 'database.php';

if (!empty($_POST)) {
    $product_id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE Products SET name = ?, description = ?, product_price = ? WHERE product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($name, $description, $price, $product_id));
    Database::disconnect();
    header("Location: user_data.php");
}
?>
