<?php
require 'database.php';

// Fetch products from the database
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT p.*, GROUP_CONCAT(c.category_name SEPARATOR ', ') as categories 
        FROM product p 
        LEFT JOIN product_category pc ON p.product_id = pc.product_product_id 
        LEFT JOIN category c ON pc.category_category_id = c.category_id 
        GROUP BY p.product_id";
$q = $pdo->prepare($sql);
$q->execute();
$products = $q->fetchAll(PDO::FETCH_ASSOC);
Database::disconnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Smart Carts</title>
    <style>
        ul.topnav {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #4CAF50;
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
</head>
<body>
    <ul class="topnav">
        <li><a href="home.php">Inicio</a></li>
        <li><a href="read_tag.php">Información de producto</a></li>
        <li><a href="associate_tag.php">Imprimir Información en etiqueta</a></li>
        <li><a href="create_campaign_category.php">Crear Categoría de Campaña</a></li>
        <li><a href="create_categories.php">Crear Categorías</a></li>
        <li><a href="create_product.php">Crear Producto</a></li>
        <li><a href="view_campaign_categories.php">Ver Categorías de Campaña</a></li>
        <li><a href="view_products.php">Ver Productos</a></li>
    </ul>
    <br>
    <div class="container">
        <div class="row">
            <h3>Product List</h3>
        </div>
        <div class="row">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr bgcolor="#10a0c5">
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Offer Option</th>
                        <th>Categories</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td><?php echo $product['offer_option'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo htmlspecialchars($product['categories']); ?></td>
                            <td>
                                <a class="btn btn-success" href="edit_product.php?id=<?php echo $product['product_id']; ?>">Edit</a>
                                <a class="btn btn-danger" href="delete_product.php?id=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
