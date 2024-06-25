<?php
    require 'database.php';
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    $pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM table_nodemcu_rfidrc522_mysql where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
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
    <title>Edit : NodeMCU V3 ESP8266 / ESP12E with MYSQL Database</title>
</head>

<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
        <li><a href="home.php">Inicio</a></li>
        <li><a href="user_data.php">Registros de productos</a></li>
        <li><a href="registration.php">Registrar producto</a></li>
        <li><a class="active" href="read_tag.php">Información de producto</a></li>
    </ul>

    <div class="container">
        <div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
            <div class="row">
                <h3 align="center">Editar información del producto</h3>
            </div>

            <form class="form-horizontal" action="user_data_edit_tb.php?id=<?php echo $id?>" method="post">
                <div class="control-group">
                    <label class="control-label">ID</label>
                    <div class="controls">
                        <input name="id" type="text" placeholder="" value="<?php echo $data['id'];?>" readonly>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">ID Producto</label>
                    <div class="controls">
                        <input name="id_product" type="text" placeholder="" value="<?php echo $data['id_product'];?>" required>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Nombre del producto</label>
                    <div class="controls">
                        <input name="name_product" type="text" placeholder="" value="<?php echo $data['name_product'];?>" required>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Descripción del producto</label>
                    <div class="controls">
                        <input name="description_product" type="text" placeholder="" value="<?php echo $data['description_product'];?>" required>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Precio del producto</label>
                    <div class="controls">
                        <input name="price_product" type="text" placeholder="" value="<?php echo $data['price_product'];?>" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a class="btn" href="user_data.php">Regresar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
