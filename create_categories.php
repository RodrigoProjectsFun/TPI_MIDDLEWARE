<?php
require 'database.php';

if (!empty($_POST)) {
    $name_category = $_POST['name_category'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO category (category_name) VALUES (?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($name_category));
    Database::disconnect();
    header("Location: user_data.php");
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
    <title>Registration : NodeMCU V3 ESP8266 / ESP12E with MYSQL Database</title>
</head>
<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
			<li><a href="home.php">Inicio</a></li>
			<li><a class="active" href="user_data.php">Registros de productos</a></li>
			<li><a href="registration.php">Registrar categoría</a></li>
			<li><a href="read_tag.php">Información de producto</a></li>
			<li><a href="associate_tag.php">Imprimir Informacion en etiqueta</a></li>
   		 </ul>

    <div class="container">
        <br>
        <div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
            <div class="row">
                <h3 align="center">Formulario de registro de categoría</h3>
            </div>
            <br>
            <form class="form-horizontal" action="create_categories.php" method="post">
                <div class="control-group">
                    <label class="control-label">Nombre de la categoría</label>
                    <div class="controls">
                        <input name="name_category" type="text" placeholder="" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
