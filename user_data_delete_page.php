<?php
require 'database.php';
$product_id = 0;

if (!empty($_GET['product_id'])) {
    $product_id = $_REQUEST['product_id'];
}

if (!empty($_POST)) {
    // keep track post values
    $product_id = $_POST['product_id'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete related records in the tags table first
    $sql = "DELETE FROM tags WHERE product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($product_id));

    // Then delete the record in the products table
    $sql = "DELETE FROM Products WHERE product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($product_id));

    Database::disconnect();
    header("Location: user_data.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <title>Delete : NodeMCU V3 ESP8266 / ESP12E with MYSQL Database</title>
</head>

<body>
    <h2 align="center">Portal Server</h2>

    <div class="container">
        <div class="span10 offset1">
            <div class="row">
                <h3 align="center">Eliminar Producto</h3>
            </div>

            <form class="form-horizontal" action="user_data_delete_page.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"/>
                <p class="alert alert-error">¿Estás seguro?</p>
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Si</button>
                    <a class="btn" href="user_data.php">No</a>
                </div>
            </form>
        </div>
    </div> <!-- /container -->
</body>
</html>
