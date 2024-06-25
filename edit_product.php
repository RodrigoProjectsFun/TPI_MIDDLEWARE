<?php
require 'database.php';

// Fetch categories
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT category_id, category_name FROM category";
$q = $pdo->prepare($sql);
$q->execute();
$categories = $q->fetchAll(PDO::FETCH_ASSOC);

// Fetch product data
$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: view_products.php");
}

if (!empty($_POST)) {
    // Get form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $offer_option = isset($_POST['offer_option']) ? 1 : 0;
    $category_ids = $_POST['category_ids'];

    // Update product data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE product SET name = ?, price = ?, description = ?, offer_option = ? WHERE product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($name, $price, $description, $offer_option, $id));

    // Update product categories
    $sql = "DELETE FROM product_category WHERE product_product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));

    foreach ($category_ids as $category_id) {
        $sql = "INSERT INTO product_category (product_product_id, category_category_id) VALUES (?, ?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($id, $category_id));
    }

    Database::disconnect();
    header("Location: view_products.php");
} else {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM product WHERE product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);

    // Fetch current product categories
    $sql = "SELECT category_category_id FROM product_category WHERE product_product_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $product_categories = $q->fetchAll(PDO::FETCH_COLUMN);
    Database::disconnect();
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
    <title>Edit Product</title>
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
                <h3 align="center">Edit Product</h3>
            </div>
            <br>
            <form class="form-horizontal" action="edit_product.php?id=<?php echo $id?>" method="post">
                <div class="control-group">
                    <label class="control-label">Product Name</label>
                    <div class="controls">
                        <input name="name" type="text" value="<?php echo htmlspecialchars($data['name']); ?>" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Price</label>
                    <div class="controls">
                        <input name="price" type="text" value="<?php echo htmlspecialchars($data['price']); ?>" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Description</label>
                    <div class="controls">
                        <textarea name="description" rows="4" required><?php echo htmlspecialchars($data['description']); ?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Offer Option</label>
                    <div class="controls">
                        <input name="offer_option" type="checkbox" <?php echo ($data['offer_option'] == 1) ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Category</label>
                    <div class="controls">
                        <select name="category_ids[]" multiple required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>" <?php echo (in_array($category['category_id'], $product_categories)) ? 'selected' : ''; ?>><?php echo $category['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
