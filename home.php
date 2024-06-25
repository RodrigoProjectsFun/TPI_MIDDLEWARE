<?php
	$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('UIDContainer.php',$Write);
?>

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
</body>
</html>
		<h3>Bienvenido al portal server</h3>
		
	</body>
</html>