<?php
require 'database.php';

if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM campaign_category WHERE campaign_category_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    Database::disconnect();
}

header("Location: view_campaign_categories.php");
?>
