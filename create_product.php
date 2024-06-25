<?php
require 'database.php';

// Fetch categories from the category table
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT category_id, category_name FROM category";
$q = $pdo->prepare($sql);
$q->execute();
$categories = $q->fetchAll(PDO::FETCH_ASSOC);
Database::disconnect();

if (!empty($_POST)) {
    // Get form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $offer_option = isset($_POST['offer_option']) ? 1 : 0;
    $category_ids = $_POST['category_ids'];

    // Insert form data into the product table
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO product (name, price, description, offer_option) VALUES (?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($name, $price, $description, $offer_option));
    $product_id = $pdo->lastInsertId();

    // Insert into product_category table
    foreach ($category_ids as $category_id) {
        $sql = "INSERT INTO product_category (product_product_id, category_category_id) VALUES (?, ?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($product_id, $category_id));
    }

    Database::disconnect();
    header("Location: view_products.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        html {
            font-family: Arial;
            display: inline-block;
            margin: 0px auto;
        }

        textarea {
            resize: none;
        }

        ul.topnav {
            list-style-type: none;
            margin: auto;
            padding: 0;
            overflow: hidden;
            background-color: #4CAF50;
            width: 70%;
        }

        ul.topnav li {
            float: left;
        }

        ul.topnav li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        ul.topnav li a:hover:not(.active) {
            background-color: #3e8e41;
        }

        ul.topnav li a.active {
            background-color: #333;
        }

        ul.topnav li.right {
            float: right;
        }

        @media screen and (max-width: 600px) {
            ul.topnav li.right,
            ul.topnav li {
                float: none;
            }
        }
    </style>
    <title>Register Product</title>
</head>
<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
        <li><a href="home.php">Home</a></li>
        <li><a class="active" href="view_products.php">Product Records</a></li>
        <li><a href="create_product.php">Register Product</a></li>
        <li><a href="read_tag.php">Product Information</a></li>
        <li><a href="associate_tag.php">Print Information on Tag</a></li>
    </ul>

    <div class="container">
        <br>
        <div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
            <div class="row">
                <h3 align="center">Product Registration Form</h3>
            </div>
            <br>
            <form class="form-horizontal" action="create_product.php" method="post">
                <div class="control-group">
                    <label class="control-label">Product Name</label>
                    <div class="controls">
                        <input name="name" type="text" placeholder="" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Price</label>
                    <div class="controls">
                        <input name="price" type="text" placeholder="" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Description</label>
                    <div class="controls">
                        <textarea name="description" rows="4" placeholder="" required></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Offer Option</label>
                    <div class="controls">
                        <input name="offer_option" type="checkbox">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Category</label>
                    <div class="controls">
                        <select name="category_ids[]" multiple required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
