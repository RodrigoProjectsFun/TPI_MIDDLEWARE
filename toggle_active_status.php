<?php
require 'database.php';

if (!empty($_POST['campaign_category_id']) && isset($_POST['current_status'])) {
    $campaign_category_id = $_POST['campaign_category_id'];
    $current_status = $_POST['current_status'];

    $new_status = $current_status ? 0 : 1;

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE campaign_category SET active = ? WHERE campaign_category_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($new_status, $campaign_category_id));
    Database::disconnect();

    echo json_encode(['new_status' => $new_status]);
}
?>
