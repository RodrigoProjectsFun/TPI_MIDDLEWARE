<?php
require 'database.php';

if (!empty($_POST)) {
    // Keep track of post values
    $id = $_POST['id'];
    $id_product = $_POST['id_product'];
    $name_product = $_POST['name_product'];
    $description_product = $_POST['description_product'];
    $price_product = $_POST['price_product'];
    
    // Check if the id already exists
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $check_sql = "SELECT COUNT(*) as count FROM table_nodemcu_rfidrc522_mysql WHERE id = ?";
    $check_query = $pdo->prepare($check_sql);
    $check_query->execute(array($id));
    $row = $check_query->fetch(PDO::FETCH_ASSOC);
    $existing_records_count = $row['count'];
    
    if ($existing_records_count > 0) {
        // If id already exists, update the record and set the updated_at field
        $update_sql = "UPDATE table_nodemcu_rfidrc522_mysql SET id_product = ?, name_product = ?, description_product = ?, price_product = ?, updated_at = NOW() WHERE id = ?";
        $update_query = $pdo->prepare($update_sql);
        $update_query->execute(array($id_product, $name_product, $description_product, $price_product, $id));
    } else {
        // If id doesn't exist, insert data with the current timestamp for created_at
        $insert_sql = "INSERT INTO table_nodemcu_rfidrc522_mysql (id, id_product, name_product, description_product, price_product, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $insert_query = $pdo->prepare($insert_sql);
        $insert_query->execute(array($id, $id_product, $name_product, $description_product, $price_product));
    }
    
    Database::disconnect();
    header("Location: user_data.php");
}
?>
